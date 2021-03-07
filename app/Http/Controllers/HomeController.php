<?php

namespace App\Http\Controllers;

use App\Model\Transaction\Sales\SalesD;
use App\Model\Transaction\Sales\SalesH;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use App\Model\Master\Category;
use App\Model\Master\Product;
use App\Model\Master\Addon;
use App\Content;
use App\User;

use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use TJGazel\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dateQuery = null; 

        if((isset($_GET["date_input"]) && (($_GET["date_input"])) !== '')){
            $dateQuery = $_GET["date_input"];
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($dateQuery)));
        }else{
            $dateQuery = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($dateQuery)));
        }

        //==========================================================================
        //DAILY OPERATIONS SUMMARY REPORT
        //==========================================================================
        
        //1. TODAY'S BEST SELLER
        $TodayBestSellerID = DB::table('sales_d')
                            ->select(DB::raw('id_product'), DB::raw('count(*) as count'))
                            ->groupBy('id_product')
                            ->orderBy('count', 'desc')
                            ->whereDate('date_order', '=',$dateQuery) 
                            ->first();

        $YesterdayBestSellerID = DB::table('sales_d')
                                ->select(DB::raw('id_product'), DB::raw('count(*) as count'))
                                ->groupBy('id_product')
                                ->orderBy('count', 'desc')
                                ->whereDate('date_order', '=',$yesterday) 
                                ->first();

        $TodayBestSeller = $TodayBestSellerID != null ? $TodayBestSeller = Product::find($TodayBestSellerID->id_product)->product_name : "N/A";
        $YesterdayBestSeller = $YesterdayBestSellerID != null ? Product::find($YesterdayBestSellerID->id_product)->product_name : "N/A";
        
        //2. NUMBER OF PRODUCTS SOLD TODAY
        $activeFlag = 1;
        $SalesToday = SalesD::whereDate('date_order', $dateQuery)
                        ->whereHas('sale', function ($salesH) use ($activeFlag) {
                            $salesH->where('active', $activeFlag);
                        })->sum('total');
        $SalesYesterday = SalesD::whereDate('date_order', $yesterday)
                        ->whereHas('sale', function ($salesH) use ($activeFlag) {
                            $salesH->where('active', $activeFlag);
                        })->sum('total');;

        //3. NET PROFIT TODAY 
        $RevenueToday = SalesH::where('active', 1)->whereDate('date', '=', $dateQuery)->sum('total');
        $RevenueYesterday = SalesH::where('active', 1)->whereDate('date', '=', $yesterday)->sum('total');
    
        //4. EXPENSES TODAY
        $ExpensesToday = PurchaseH::where('active', 1)->whereDate('date', '=', $dateQuery)->sum('total');
        $ExpensesYesterday = PurchaseH::where('active', 1)->whereDate('date', '=', $yesterday)->sum('total');

        $ExpensivePurchaseHToday =  PurchaseH::where('active', 1)->whereDate('date', '=', $dateQuery)
                                    ->orderBy('total', 'desc')->first();
                                    
        $ExpensesPercentageChange = $this->percentageChangeCalculator($ExpensesYesterday, $ExpensesToday) ?? "N/A";
        $SalesPercentageChange = $this->percentageChangeCalculator($SalesYesterday, $SalesToday) ?? "N/A";
        $ProfitPercentageChange = $this->percentageChangeCalculator($RevenueYesterday, $RevenueToday) ?? "N/A";
        
        $ExpensivePurchaseDToday = $ExpensivePurchaseHToday != null ? DB::table('purchase_d')
                                                        ->where('id_purchase', '=', $ExpensivePurchaseHToday->id)
                                                        ->orderBy('price', 'desc')
                                                        ->first() : "N/A";

        //1. HIGHEST NUMBER OF PRODUCT SALES
        $HighestSales = SalesD::whereDate('date_order', '=', $dateQuery)->orderBy('total', 'desc')->first();
        
        $HighestSalesToday = null;
        $SalesHInformation = null;

        if($HighestSales != null){

            $SalesHToday = SalesD::where('id_sales', $HighestSales->id_sales)->whereDate('date_order', '=', $dateQuery)
                            ->orderBy('total', 'desc')->get();

            for($i=0; $i<count($SalesHToday); $i++){
                $HighestSalesToday += $SalesHToday[$i]->total;
            }

            $SalesHInformation = SalesH::select('customer')->where('active', 1)->where('id', '=', $HighestSales->id_sales)
                                ->whereDate('date', '=', $dateQuery)->first();
        }

        //2. HIGHEST REVENUE FROM A CUSTOMER
        $HighestRevenue = SalesH::where('active', 1)->whereDate('date', '=', $dateQuery)->orderBy('total', 'desc')
                                ->first();

        $HighestRevenueToday = null;

        if($HighestRevenue != null){

            $CustomerPurchasesToday = SalesH::where('active', 1)->where('customer', '=', $HighestRevenue->customer)
                                        ->whereDate('date', '=', $dateQuery)->orderBy('total', 'desc')->get();
                                        
            for($i=0; $i<count($CustomerPurchasesToday); $i++){
                $HighestRevenueToday += $CustomerPurchasesToday[$i]->total;
            }

        }
        
        //2. NET PROFIT BLOCK
        $NetProfit = $RevenueToday - $ExpensesToday;

        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //TOP 10 BEST SELLERS
        $TenBestSellers = DB::table('sales_d')
                        ->select(DB::raw('id_product'), DB::raw('count(*) as count'))
                        ->groupBy('id_product')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
                        
        foreach($TenBestSellers as $BestSeller){
            $currentProduct = $BestSeller->{'id_product'};
            $productName = Product::find($currentProduct);
            $BestSeller->{'product_name'} = $productName->{'product_name'};
        }

        //SALES BASED ON PRODUCTS - DONUT CHART
        $NamesArray = [];
        $ValuesArray = [];

        $CategoriesSales = 0;

        $categories = Category::where('category_status', '1')->withCount("category_sales")
                                ->orderBy("category_sales_count", "desc")->get();
        if(count($categories) > 0)
        {
            for($x=0; $x<count($categories); $x++){
                $NamesArray[$x] = $categories[$x]->category_name;
                $ValuesArray[$x] = $categories[$x]->category_sales_count;
                $CategoriesSales += $categories[$x]->product_sales_count;
            }
        }

        //NUMBER OF ORDERS PER DAY
        $OrderDays = SalesH::select('date', 'total')->where('date', '!=', '0000-00-00')->orderBy('date', 'desc')
                        ->get();
                       
        $DayRecords = range(0, 7);
        
        if(count($OrderDays) > 0){
            for($o = 0; $o < count($OrderDays); $o++)
            {
                $currentDate = new DateTime($OrderDays[$o]->date);
                $currentDay = $currentDate->format('l');
                
                if($currentDay == "Sunday"){
                    $DayRecords[0] += 1; 
                }else if($currentDay == "Monday"){
                    $DayRecords[1] += 1; 
                }else if($currentDay == "Tuesday"){
                    $DayRecords[2] += 1; 
                }else if($currentDay == "Wednesday"){
                    $DayRecords[3] += 1; 
                }else if($currentDay == "Thursday"){
                    $DayRecords[4] += 1; 
                }else if($currentDay == "Friday"){
                    $DayRecords[5] += 1; 
                }else if($currentDay == "Saturday"){
                    $DayRecords[6] += 1; 
                }
            }
        }else{
            $DayRecords = [];
        }

        //PRODUCTS BY STATUS
        $Inactive_Products = Product::where('product_active', 0)->count();
        $Active_Products = Product::where('product_active', 1)->count();

        //PURCHASE AND REVENUE
        $Purchase_Max = PurchaseH::sum('total');
        $Sales_Max = SalesH::sum('total');

        //MOST 5 FREQUENT CUSTOMERS
        $MostLoyalCustomer = DB::table('sales_h')
                                ->select('customer', DB::raw('COUNT(*) AS count'), DB::raw('SUM(total) as total'))
                                ->where('customer', '!=', 'N/A')
                                ->groupBy('customer')
                                ->orderByDesc('count')
                                ->limit(5)
                                ->get();

        $RecentlyAddedProducts = Product::where('product_active', 1)->orderByDesc('created_at')->limit(5)->get();

        //==========================================================================
        //MONTHLY OPERATIONS SUMMARY REPORT
        //==========================================================================

        $dateRecapQuery = null; 

        if((isset($_GET["date_recap"]) && (($_GET["date_recap"])) !== '')){
            $dateRecapQuery = $_GET["date_recap"];
            //$previousMonth = date('Y-m', strtotime('-1 months', strtotime($dateRecapQuery)));
        }else{
            $dateRecapQuery = date('Y-m');
            //$previousMonth = date('Y-m', strtotime('-1 months', strtotime($dateRecapQuery)));
        }

        $currentMonth = new Carbon($dateRecapQuery);
        $CurrentMonthDisplay = $currentMonth->format('F')." ".$currentMonth->format('Y');
        
        $firstDayMonth = $currentMonth->startOfMonth()->modify('0 month')->toDateString();
        $lastDayMonth = $currentMonth->endOfMonth()->modify('0 month')->toDateString();
    
        //Previous Month 
        $firstDayPreviousMonth = $currentMonth->subMonth()->startOfMonth()->modify('0 month')->toDateString();
        $lastDayPreviousMonth = $currentMonth->endOfMonth()->modify('0 month')->toDateString();
        
        //1. ================================================= EXPENSES ================================================= 

        //Monthly Purchase/Expense Record
        $MonthlyExpenses = PurchaseH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                                ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total) as total'))
                                ->where('active', 1)
                                ->groupBy('date')
                                ->get();
                                //dd($MonthlyExpenses);
                                
        $CurrentMonthExpenseTotal = $MonthlyExpenses->sum('total');
        $PreviousMonthExpenseTotal = (int)PurchaseH::whereBetween('date', [$firstDayPreviousMonth, $lastDayPreviousMonth])
                                                ->where('active', 1)->sum('total');

        $CurrentMonthExpensePercentage = $this->percentageChangeCalculator($CurrentMonthExpenseTotal, $PreviousMonthExpenseTotal);              
        $CurrentMonthAverageExpensePercentage = $this->percentageChangeCalculator($CurrentMonthExpenseTotal, $PreviousMonthExpenseTotal);  
        
        
        $HighestMonthlyExpense = $MonthlyExpenses->where('total', $MonthlyExpenses->max('total'))->first() ?? null;
        $LowestMonthlyExpense = $MonthlyExpenses->where('total', $MonthlyExpenses->min('total'))->first() ?? null;
        $AverageMonthlyExpense = count($MonthlyExpenses) > 0 ? round($CurrentMonthExpenseTotal/count($MonthlyExpenses)) : null;

        //2. ================================================= REVENUE ================================================= 
        
        $MonthlyRevenue = SalesH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                                ->select(DB::raw('date'), DB::raw('SUM(total) as total'))
                                ->where('active', 1)
                                ->groupBy('date')
                                ->get();

        $PreviousMonthlyRevenue = SalesH::whereBetween('date', [$firstDayPreviousMonth, $lastDayPreviousMonth])
                                        ->select(DB::raw('date'), DB::raw('SUM(total) as total'))
                                        ->where('active', 1)
                                        ->groupBy('date')
                                        ->get();
       
        $CurrentMonthRevenueTotal = $MonthlyRevenue->sum('total');
        $PreviousMonthRevenueTotal = $PreviousMonthlyRevenue->sum('total');
        
        //Get the percentage up/down rate
        $CurrentMonthRevenuePercentage = $this->percentageChangeCalculator($CurrentMonthRevenueTotal-$PreviousMonthRevenueTotal, $CurrentMonthExpenseTotal-$PreviousMonthExpenseTotal);  
        $CurrentMonthAverageRevenuePercentage = $this->percentageChangeCalculator($CurrentMonthRevenueTotal, $PreviousMonthRevenueTotal);  
        
        $HighestMonthlyRevenue = $MonthlyRevenue->where('total', $MonthlyRevenue->max('total'))->first() ?? null;
        $LowestMonthlyRevenue = $MonthlyRevenue->where('total', $MonthlyRevenue->min('total'))->first() ?? null;
        $AverageMonthlyRevenue = count($MonthlyRevenue) > 0 ? round($CurrentMonthRevenueTotal/count($MonthlyRevenue)) : null;
        
        $CurrentMonthProfitTotal = abs($CurrentMonthRevenueTotal-$CurrentMonthExpenseTotal);
        $PreviousMonthProfitTotal = abs($PreviousMonthRevenueTotal-$PreviousMonthExpenseTotal);
        $AverageDailyNetProfit = count($MonthlyRevenue) > 0 ? round($CurrentMonthProfitTotal/count($MonthlyRevenue)) : null;

        $PreviousAverageDailyNetProfit = count($MonthlyRevenue) > 0 && count($PreviousMonthlyRevenue) > 0 ? round(abs($CurrentMonthProfitTotal-$PreviousMonthProfitTotal)/count($PreviousMonthlyRevenue)) : null;
        $CurrentMonthProfitPercentage = $this->percentageChangeCalculator($CurrentMonthRevenueTotal, $PreviousMonthRevenueTotal);  
        $CurrentMonthAverageProfitPercentage = $this->percentageChangeCalculator($AverageDailyNetProfit, $PreviousMonthRevenueTotal);  
        
        //3. ================================================= ORDERS ================================================= 

        $MonthlyOrder = SalesH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                        ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
                        ->where('active', 1)
                        ->groupBy('date')
                        ->get();

        $PreviousMonthlyOrder = SalesH::whereBetween('date', [$firstDayPreviousMonth, $lastDayPreviousMonth])
                        ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
                        ->where('active', 1)
                        ->groupBy('date')
                        ->get();

        $CurrentMonthOrderTotal = $MonthlyOrder->sum('total');
        $PreviousMonthOrderTotal = SalesH::whereBetween('date', [$firstDayPreviousMonth, $lastDayPreviousMonth])
                                            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
                                            ->where('active', 1)->groupBy('date')->get()->sum('total');
 
        $CurrentMonthOrderPercentage = $this->percentageChangeCalculator($CurrentMonthOrderTotal, $PreviousMonthOrderTotal);  
        $HighestMonthlyOrder = $MonthlyOrder->where('total', $MonthlyOrder->max('total'))->first() ?? null;
        $LowestMonthlyOrder = $MonthlyOrder->where('total', $MonthlyOrder->min('total'))->first() ?? null;
        $AverageMonthlyOrder = count($MonthlyOrder) > 0 ? round($CurrentMonthOrderTotal/count($MonthlyOrder)) : null;
        $AverageMonthOrderPercentage = $this->percentageChangeCalculator($AverageMonthlyOrder, $PreviousMonthOrderTotal);  
                       
        //MONTHLY AREA CHART VALUES
        $MonthlyOrderValues = [];

        for($m=0; $m<count($MonthlyOrder); $m++)
        {
            $MonthlyOrder[$m]->setAttribute('day', DateTime::createFromFormat("Y-m-d",$MonthlyOrder[$m]->date)->format('d'));
        }

        //4. ================================================= RECORDS ================================================= 
        
        $NumberOfNetLossDays = 0;
        $NumberOfNetProfitDays = 0;
        
        //Monthly Records 
        $MonthlyRecords = [];
        
        if(count($MonthlyRevenue) > count($MonthlyExpenses)) //REVENUE
        {
            for($x=0; $x<count($MonthlyRevenue); $x++)
            {
                for($y=0; $y<count($MonthlyExpenses); $y++)
                {
                    if(isset($MonthlyRevenue[$x]->date) && isset($MonthlyExpenses[$y]->date))
                    {
                        if($MonthlyRevenue[$x]->date == $MonthlyExpenses[$y]->date)
                        {
                            $MonthlyRecords[$x]['date'] = date('m.d.Y', strtotime($MonthlyRevenue[$x]->date)) ." - ". date("l", strtotime($MonthlyRevenue[$x]->date));
                            $MonthlyRecords[$x]['sales_total'] = (int)$MonthlyRevenue[$x]->total;
                            $MonthlyRecords[$x]['revenue_total'] = (int)abs($MonthlyExpenses[$y]->total - $MonthlyRevenue[$x]->total);
                            $MonthlyRecords[$x]['expense_total'] = (int)$MonthlyExpenses[$y]->total;
                            $MonthlyRecords[$x]['order_total'] = (int)$MonthlyOrder[$x]->total;
                            
                            if(isset($MonthlyRecords[$x]['expense_total']) && isset($MonthlyRecords[$x]['revenue_total'])){
                                $MonthlyRecords[$x]['revenue_total'] > $MonthlyRecords[$x]['expense_total'] ? $NumberOfNetLossDays++ : $NumberOfNetProfitDays++;
                            }
                        }
                    }
                }
            }
        }
        else if(count($MonthlyExpenses) > count($MonthlyRevenue)) //EXPENSES
        {
            for($y=0; $y<count($MonthlyExpenses); $y++)
            {
                for($x=0; $x<count($MonthlyRevenue); $x++)
                {
                    if(isset($MonthlyExpenses[$y]->date) && isset($MonthlyRevenue[$y]->date))
                    {
                        if($MonthlyExpenses[$y]->date == $MonthlyRevenue[$y]->date)
                        {
                            $MonthlyRecords[$y]['date'] = date('m.d.Y', strtotime($MonthlyExpenses[$y]->date)) ." - ". date("l", strtotime($MonthlyExpenses[$y]->date));
                            $MonthlyRecords[$y]['sales_total'] = (int)$MonthlyRevenue[$y]->total;
                            $MonthlyRecords[$y]['revenue_total'] = (int)abs($MonthlyExpenses[$y]->total - $MonthlyRevenue[$x]->total);
                            $MonthlyRecords[$y]['expense_total'] = (int)$MonthlyExpenses[$y]->total;
                            $MonthlyRecords[$y]['order_total'] = (int)$MonthlyOrder[$y]->total;

                            if(isset($MonthlyRecords[$x]['expense_total']) && isset($MonthlyRecords[$x]['revenue_total'])){
                                $MonthlyRecords[$x]['expense_total'] > $MonthlyRecords[$x]['revenue_total'] ? $NumberOfNetLossDays++ : $NumberOfNetProfitDays++;
                            }
                        }
                    }
                }
            }
        }

        //Re-Arrange in case there's skipped index
        $MonthlyRecords = array_values($MonthlyRecords);
        
        $array = compact(
        'yesterday',
        'Inactive_Products', 
        'Active_Products', 
        'Purchase_Max',
        'Sales_Max', 
        'TenBestSellers',
        'TodayBestSeller',
        'YesterdayBestSeller',
        'SalesToday', 
        'SalesYesterday',
        'SalesPercentageChange',
        'NetProfit',
        'RevenueToday', 
        'RevenueYesterday',
        'ProfitPercentageChange',
        'NamesArray',
        'ValuesArray',
        'CategoriesSales',
        'ExpensesToday',
        'ExpensesYesterday',
        'ExpensesPercentageChange',
        'ExpensivePurchaseDToday',
        'HighestRevenue',
        'HighestRevenueToday',
        'HighestSalesToday',
        'SalesHInformation',
        'DayRecords',
        'MostLoyalCustomer',
        'RecentlyAddedProducts',
        'firstDayMonth', 
        'lastDayMonth',
        'MonthlyExpenses',
        'MonthlyRevenue',
        'MonthlyOrder',
        'AverageMonthlyOrder',
        'AverageMonthOrderPercentage',
        'CurrentMonthAverageRevenuePercentage',
        'CurrentMonthOrderPercentage',
        'CurrentMonthExpenseTotal',
        'CurrentMonthDisplay',
        'HighestMonthlyRevenue',
        'AverageMonthlyRevenue',
        'CurrentMonthProfitTotal',
        'PreviousAverageDailyNetProfit',
        'AverageDailyNetProfit',
        'CurrentMonthRevenueTotal',
        'CurrentMonthOrderTotal',
        'CurrentMonthRevenuePercentage',
        'CurrentMonthProfitPercentage',
        'LowestMonthlyRevenue',
        'CurrentMonthAverageExpensePercentage',
        'CurrentMonthExpensePercentage',
        'CurrentMonthRevenuePercentage',
        'HighestMonthlyExpense',
        'LowestMonthlyExpense',
        'AverageMonthlyExpense', 
        'dateQuery', 
        'MonthlyRecords',
        'NumberOfNetLossDays',
        'NumberOfNetProfitDays'); 

        return view('/home', $array);
    }

    public function expensesSummary()
    {
        $dateQuery = null; 

        if((isset($_GET["date_input"]) && (($_GET["date_input"])) !== '')){
            $dateQuery = $_GET["date_input"];
        }else{
            $dateQuery = date('yy-m-d');
        }

        $ExpensesToday = 0;
        $FinalExpensesRecord = [];

        $Expenses = PurchaseH::with(['user_modify'])->whereDate('date', '=', $dateQuery)->get();

        for($i=0; $i<count($Expenses); $i++)
        {
            $FinalExpensesRecord[$i]['user'] = $Expenses[$i]->user_modify->first_name." ".$Expenses[$i]->user_modify->last_name;
            $FinalExpensesRecord[$i]['total'] = $Expenses[$i]->total;

            $itemsExpenses = PurchaseD::where('id_purchase', '=', $Expenses[$i]->id)->get();
            $FinalExpensesRecord[$i]['items'] = "";

            for($j=0; $j<count($itemsExpenses); $j++){

                if($j == count($itemsExpenses)-1){
                    $FinalExpensesRecord[$i]['items'] .= $itemsExpenses[$j]->product_name;
                }else{
                    $FinalExpensesRecord[$i]['items'] .= $itemsExpenses[$j]->product_name. ", ";
                }
            }
        }

        $ExpensesToday = DB::table('purchase_h')->whereDate('date', '=', $dateQuery)->sum('total');
        $array = compact('dateQuery', 'FinalExpensesRecord', 'ExpensesToday');
        return view('/expensesSummary', $array);
    }

    public function salesSummary()
    {
        $dateQuery = null; 

        if((isset($_GET["date_input"]) && (($_GET["date_input"])) !== '')){
            $dateQuery = $_GET["date_input"];
        }else{
            $dateQuery = date('yy-m-d');
        }

        $SalesToday = 0;
        $ProductsSold = 0;
        $FinalSalesRecord = [];

        $Sales = SalesH::where('active', 1)
                        ->whereDate('date', '=', $dateQuery)->get();

        for($x=0; $x<count($Sales); $x++)
        {
            $FinalSalesRecord[$x]['customer'] = $Sales[$x]->customer;
            $FinalSalesRecord[$x]['total_price'] = $Sales[$x]->total;

            $sales_d = SalesD::where('id_sales', $Sales[$x]->id)
                        ->whereDate('date_order', '=', $dateQuery)
                        ->orderBy('id_sales', 'desc')
                        ->get();

            $currentProductName = "";
            $currentProductQuantity = "";
            $salesHTotal = null;

            for($y=0; $y<count($sales_d); $y++)
            {
                $product = Product::find($sales_d[$y]->id_product);

                if($y != count($sales_d)-1)
                {
                    $currentProductName .= $product->product_name. ",";
                    $currentProductQuantity .= $sales_d[$y]->total. ",";
                }else
                {
                    $currentProductName .= $product->product_name;
                    $currentProductQuantity .= $sales_d[$y]->total;
                }
                
                $FinalSalesRecord[$x]['products'] = $currentProductName;
                $FinalSalesRecord[$x]['quantity'] = $currentProductQuantity;
                $ProductsSold += $sales_d[$y]->total;
            }
        }

        $SalesToday = SalesH::whereDate('date', '=', $dateQuery)->sum('total');
        $array = compact('dateQuery', 'FinalSalesRecord', 'SalesToday', 'ProductsSold');
        
        return view('/salesSummary', $array);
    }

    public function categoriesSummary()
    {
        $dateQuery = isset($_GET["date_input"]) && $_GET["date_input"] !== '' ? $_GET["date_input"] : date('yy-m-d'); 
        
        $SalesToday = 0;
        $content = Content::first();

        $categories = Category::where('category_status', 1)->orderBy("category_name", "desc")->get();
        $sales_d = SalesD::with(['product', 'sale'])->whereDate('date_order', '=',$dateQuery)->get();
                    
        if(count($sales_d) < 0){
            $array = compact('dateQuery', 'categories', 'content');
            return view ('/categoriesSummary', $array);
        }
        
        for($x=0; $x<count($categories); $x++)
        {
            for($i = 0; $i<count($sales_d); $i++)
            {

                $currentProductCategory = Category::with(['product'], 'product_id', $sales_d[$i]->id_product)
                                                    ->where('category_id', $sales_d[$i]->product->product_type)
                                                    ->first();
                                                    
                if($currentProductCategory->category_id == $categories[$x]->category_id){

                    $categories[$x]['quantity'] += $sales_d[$i]->total;
                    //$categories[$x]['total'] += $sales_d[$i]->sale->total;
                    $categories[$x]['total'] += ($sales_d[$i]->price * $sales_d[$i]->total);
                    $categories[$x]['category'] = $currentProductCategory->category_name;
                }

            }
        }

        $QtyToday = SalesD::whereDate('date_order', '=', $dateQuery)->sum('total');
        $SalesToday = SalesH::whereDate('date', '=', $dateQuery)->sum('total');
                        
        $array = compact('categories', 'SalesToday', 'QtyToday', 'dateQuery', 'content');
        return view ('/categoriesSummary', $array);
    }

    public function Calendar()
    {
        $activeFlag=1;

        $Sales = SalesH::select(DB::raw('date'), DB::raw('SUM(total) as total'))->where('active', 1)->groupBy('date')->get();
        $Purchase = PurchaseH::select(DB::raw('date'), DB::raw('SUM(total) as total'))->where('active', 1)->groupBy('date')->get();
        $Orders = SalesD::select(DB::raw('DATE(date_order) as date'), DB::raw('count(*) as total'))
                        ->whereHas('sale', function ($salesH) use ($activeFlag) {
                            $salesH->where('active', $activeFlag);
                        })->groupBy('date')->get();
        
        return view('/calendar', compact('Sales', 'Purchase', 'Orders'));
    }

    public function RecentActivities()
    {
        $data = Activity::with('user', 
                                'content',
                                'product', 
                                'category',
                                'purchase_d', 
                                'purchase_h', 
                                'sales_d', 
                                'sales_h', 
                                'stock',
                                'sales_addon',
                                'addons')
                            ->select('log_name', 'causer_id','description', 'subject_id', 'created_at')
                            ->orderBy('id', 'DESC')->take($content->max_activities ?? 1000)->get();

        return Datatables::of($data)
        ->editColumn('subject_id', function($data){
            
            if($data->log_name == "User")
            {
                $retrieved_first_name = $data->user->first_name ?? '';
                $retrieved_last_name = $data->user->last_name ?? '';
                return $retrieved_first_name. " ". $retrieved_last_name;
            }
            else if($data->log_name == "Product"){
                $product_name = $data->product->product_name ?? '';
                return "Product Name: ".$product_name; 
            }
            else if($data->log_name == "PurchaseD" || $data->log_name == "PurchaseH"){
                $retrieved_purchase_invoice = $data->purchase_h->no_invoice ?? '';
                return "Invoice #".$retrieved_purchase_invoice;
            }
            else if($data->log_name == "SalesD" || $data->log_name == "SalesH"){
                $retrieved_sales_invoice = $data->sales_h->invoice_no ?? '';
                return "Invoice #".$retrieved_sales_invoice;
            }
            else if($data->log_name == "Stock"){
                $retrieved_product = $data->product->product_name ?? '';
                $retrieved_stock = $data->stock->total ?? '';

                return "Product (".$retrieved_product.") Stock Total: ". $retrieved_stock;
            }
            else if($data->log_name == "Category"){
                $retrieved_category = $data->category->category_name ?? '';

                return "Product Category: ". $retrieved_category;
            }
            else if($data->log_name == "Content"){
                $retrieved_content = $data->content->id ?? '';

                return "Content ID: ". $retrieved_content;
            }
            else if($data->log_name == "Sale Addon"){
                $retrieved_content = $data->content->id ?? '';

                return "Sale Addon: ". $retrieved_content;
            }
        })->editColumn('created_at', function($data){
            return date('d M Y - H:i:s', $data->created_at->timestamp);
        })->make(true);
    }

    public function percentageChangeCalculator($YesterdayValue, $TodayValue)
    {
        $PercentageChange = 0;

        if($YesterdayValue != null && $TodayValue != null){
            $PercentageChange = round(abs((($YesterdayValue - $TodayValue) / $TodayValue)) * 100, 0);
        }else{
            $PercentageChange = null;
        }

        return $PercentageChange;
    }
}
