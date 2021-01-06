<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use TJGazel\Toastr\Facades\Toastr;
use Yajra\DataTables\DataTables;
use App\Model\Master\Product;
use App\Model\Master\Category;
use App\Model\Transaction\Sales\SalesD;
use App\Model\Transaction\Sales\SalesH;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\User;
use DateTime;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$DateToday = date('yy-m-d', strtotime("yesterday"));

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

        $TodayBestSeller = null;
        $YesterdayBestSeller = null;
        
        if($TodayBestSellerID != null || $TodayBestSellerID != ""){
            $TodayBestSeller = Product::find($TodayBestSellerID->id_product)->product_name;
        }

        if($YesterdayBestSellerID != null || $YesterdayBestSellerID != ""){
            $YesterdayBestSeller = Product::find($YesterdayBestSellerID->id_product)->product_name;
        }
        
        //2. NUMBER OF PRODUCTS SOLD TODAY
        $SalesToday = DB::table('sales_d')
                        ->where('id_product', '!=', '102') //Temporary condition
                        ->whereDate('date_order', '=', $dateQuery)
                        ->sum('total');

        $SalesYesterday = DB::table('sales_d')
                        ->where('id_product', '!=', '102') //Temporary condition
                        ->whereDate('date_order', '=', $yesterday)
                        ->sum('total');

        $SalesPercentageChange = $this->percentageChangeCalculator($SalesYesterday, $SalesToday);

        //3. NET PROFIT TODAY 
        $RevenueToday = DB::table('sales_h')
                    ->where('active', '=', 1)
                    ->whereDate('date', '=', $dateQuery)
                    ->sum('total');

        $RevenueYesterday = DB::table('sales_h')
                    ->where('active', '=', 1)
                    ->whereDate('date', '=', $yesterday)
                    ->sum('total');

        $ProfitPercentageChange = $this->percentageChangeCalculator($RevenueYesterday, $RevenueToday);

        //4. EXPENSES TODAY
        $ExpensesToday = DB::table('purchase_h')
                            ->where('active', '=', 1)
                            ->whereDate('date', '=', $dateQuery)
                            ->sum('total');
        
        $ExpensesYesterday = DB::table('purchase_h')
                                ->where('active', '=', 1)
                                ->whereDate('date', '=', $yesterday)
                                ->sum('total');

        $ExpensesPercentageChange = $this->percentageChangeCalculator($ExpensesYesterday, $ExpensesToday);

        $ExpensivePurchaseHToday = DB::table('purchase_h')
                                    ->where('active', '=', 1)
                                    ->whereDate('date', '=', $dateQuery)
                                    ->orderBy('total', 'desc')
                                    ->first();
        
        $ExpensivePurchaseDToday = null;
        
        if($ExpensivePurchaseHToday != null){
            $ExpensivePurchaseDToday = DB::table('purchase_d')
                                        ->where('id_purchase', '=', $ExpensivePurchaseHToday->id)
                                        ->orderBy('price', 'desc')
                                        ->first();
        }
        
        //1. HIGHEST NUMBER OF PRODUCT PURCHASES
        $HighestSales = DB::table('sales_d')
                            ->where('id_product', '!=', 102)
                            ->whereDate('date_order', '=', $dateQuery)
                            ->orderBy('total', 'desc')
                            ->first();
        
        $HighestSalesToday = null;
        $SalesHInformation = null;

        if($HighestSales != null){

            $SalesHToday = DB::table('sales_d')
                            ->where('id_sales', '=', $HighestSales->id_sales)
                            ->whereDate('date_order', '=', $dateQuery)
                            ->orderBy('total', 'desc')
                            ->get();

            for($i=0; $i<count($SalesHToday); $i++){
                $HighestSalesToday += $SalesHToday[$i]->total;
            }

            $SalesHInformation = DB::table('sales_h')
                                ->select('shop_name')
                                ->where('active', '=', 1)
                                ->where('id', '=', $HighestSales->id_sales)
                                ->whereDate('date', '=', $dateQuery)
                                ->first();
        }

        //2. HIGHEST REVENUE FROM A CUSTOMER
        $HighestRevenue = DB::table('sales_h')
                                ->where('active', '=', 1)
                                ->whereDate('date', '=', $dateQuery)
                                ->orderBy('total', 'desc')
                                ->first();

        $HighestRevenueToday = null;

        if($HighestRevenue != null){

            $CustomerPurchasesToday = DB::table('sales_h')
                                        ->where('active', '=', 1)
                                        ->where('shop_name', '=', $HighestRevenue->shop_name)
                                        ->whereDate('date', '=', $dateQuery)
                                        ->orderBy('total', 'desc')
                                        ->get();
                                        
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
                        ->where('id_product', '!=', 102)
                        ->groupBy('id_product')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
                        
        foreach($TenBestSellers as $BestSeller){
            $currentProduct = $BestSeller->{'id_product'};
            
            $productName = DB::table('products')
                            ->select('product_name')
                            ->where('product_id', '=', $currentProduct)
                            ->first();
                            
            $BestSeller->{'product_name'} = $productName->{'product_name'};
        }

        //INCOME BASED ON PRODUCTS - DONUT CHART
        $NamesArray = [];
        $ValuesArray = [];
        $CategoriesSales = 0;

        $categories = Category::where('category_status', '=', '1')
                                ->where('category_id', '!=', '9')
                                ->withCount("category_sales")
                                ->orderBy("category_sales_count", "desc")
                                ->get();
                               
        for($x=0; $x<count($categories); $x++){
            $NamesArray[$x] = $categories[$x]->category_name;
            $ValuesArray[$x] = $categories[$x]->category_sales_count;
            $CategoriesSales += $categories[$x]->product_sales_count;
        }

        //NUMBER OF ORDERS PER DAY
        $OrderDays = DB::table('sales_h')
                        ->select('date', 'total')
                        ->where('date', '!=', '0000-00-00')
                        ->orderBy('date', 'desc')
                        ->get();
                        
        $DayRecords = range(0, 7);

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

        //PRODUCTS BY STATUS
        $Inactive_Products = \DB::table('products')->where('product_active', '=', 0)->count();
        $Active_Products = \DB::table('products')->where('product_active', '=', 1)->count();
        $Deleted_Products = \DB::table('products')->where('product_active', '=', 2)->count();

        //PURCHASE AND SALES
        $Purchase_Max = \DB::table('purchase_h')->sum('total');
        $Sales_Max = \DB::table('sales_h')->sum('total');

        //MOST 5 FREQUENT CUSTOMERS
        $MostLoyalCustomer = DB::table('sales_h')
                                ->select('shop_name', DB::raw('COUNT(*) AS count'), DB::raw('SUM(total) as total'))
                                ->where('shop_name', '!=', 'N/A')
                                ->groupBy('shop_name')
                                ->orderByDesc('count')
                                ->limit(5)
                                ->get();

        $RecentlyAddedProducts = DB::table('products')
                                ->select('*')
                                ->where('product_active', '=', 1)
                                ->orderByDesc('created_at')
                                ->limit(5)
                                ->get();

        //==========================================================================
        //MONTHLY OPERATIONS SUMMARY REPORT
        //==========================================================================

        //ORIGINAL CODE
        $MonthStart = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $MonthEnd = \Carbon\Carbon::now()->endOfMonth()->toDateString();

        //DEBUG CODE
        //$MonthStart = \Carbon\Carbon::now()->subDays(30)->startOfMonth()->toDateString();
        //$MonthEnd = \Carbon\Carbon::now()->subDays(30)->endOfMonth()->toDateString();
        
        //ORIGINAL CODE
        $firstDayMonth = \Carbon\Carbon::now()->startOfMonth()->modify('0 month')->toDateString();
        $lastDayMonth = \Carbon\Carbon::now()->endOfMonth()->modify('0 month')->toDateString();
        
        //Get current Month
        $currentMonth = \Carbon\Carbon::now();
        $CurrentMonth = $currentMonth->format('F');

        //1. EXPENSES

        //Monthly Purchase/Expense Record
        $MonthlyExpenses = PurchaseH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                                ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total) as total'))
                                ->where('active', '=', 1)
                                ->groupBy('date')
                                ->get();

        //Get the lowest expense record                       
        $HighestMonthlyExpense = $MonthlyExpenses->where('total', $MonthlyExpenses->max('total'))->first();
        //Get the highest expense record
        $LowestMonthlyExpense = $MonthlyExpenses->where('total', $MonthlyExpenses->min('total'))->first();
        
        //Get the Average Monthly Expense
        $AverageMonthlyExpense = 0;
        $MonthlyExpenseValues = [];

        for($m=0; $m<count($MonthlyExpenses); $m++)
        {
            $MonthlyExpenseValues[$m] = $MonthlyExpenses[$m]->total;
        }

        if(count($MonthlyExpenses) > 0){
            $AverageMonthlyExpense = round(array_sum($MonthlyExpenseValues)/count($MonthlyExpenseValues));
        }

        //2. PRODUCTS SOLD

        //Get Monthly Sold Products
        $MonthlySoldProducts = SalesD::whereBetween('date_order', [$firstDayMonth, $lastDayMonth])
                                    ->select(DB::raw('date_order'), DB::raw('SUM(total) as total'))
                                    ->where('id_product', '!=', '102')
                                    ->groupBy('date_order')
                                    ->get();

        $HighestMonthlySoldProducts = 0;
        $LowestMonthlySoldProducts = 0;
        $Date_HighestMonthlySoldProducts = "N/A";
        $Date_LowestMonthlySoldProducts = "N/A";

        if(count($MonthlySoldProducts) > 0){
            $Date_HighestMonthlySoldProducts = $MonthlySoldProducts[0]->date_order;
            $LowestMonthlySoldProducts = $MonthlySoldProducts[0]->total;
            $Date_LowestMonthlySoldProducts = $MonthlySoldProducts[0]->date_order;

            //Finds the maximum 'Total'
            for($k=0; $k<count($MonthlySoldProducts); $k++)
            {
                if($HighestMonthlySoldProducts < $MonthlySoldProducts[$k]->total){
                    $HighestMonthlySoldProducts = $MonthlySoldProducts[$k]->total;
                    $Date_HighestMonthlySoldProducts = $MonthlySoldProducts[$k]->date_order;
                }   
            }

            //Finds the minimum 'Total'
            for($k=0; $k<count($MonthlySoldProducts); $k++)
            {
                if($LowestMonthlySoldProducts > $MonthlySoldProducts[$k]->total){
                    $LowestMonthlySoldProducts = $MonthlySoldProducts[$k]->total;
                    $Date_LowestMonthlySoldProducts = $MonthlySoldProducts[$k]->date_order;
                }
            }
        }
        
        //3. ORDERS
        
        //Monthly Sales (No. of Orders) Record
        $MonthlySales = SalesH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                        ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
                        ->where('active', '=', 1)
                        ->groupBy('date')
                        ->get();

        //MONTHLY AREA CHART VALUES
        $MonthlySalesDays = [];
        $MonthlySalesValues = [];

        $PreviousChecker = -1;
        
        for($m=0; $m<count($MonthlySales); $m++)
        {
            $currentDate = DateTime::createFromFormat("Y-m-d",$MonthlySales[$m]->date);
            $MonthlySalesDays[$m] = $currentDate->format('d');

            $MonthlySalesValues[$m] = $MonthlySales[$m]->total;
        }


        //Get Average Monthly Order
        $AverageMonthlyOrder = 0;
        
        if(count($MonthlySalesValues) > 0){
            $AverageMonthlyOrder = round(array_sum($MonthlySalesValues)/count($MonthlySalesValues));
        }

        //4. REVENUES

        //Monthly Sales (Profit) Record
        $MonthlyRevenue = SalesH::whereBetween('date', [$firstDayMonth, $lastDayMonth])
                        ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total) as total'))
                        ->where('active', '=', 1)
                        ->groupBy('date')
                        ->get();
                        
        $MontlyProfitValues = [];
        $AverageMonthlyRevenue = 0;
        $AverageDailyProfit = 0;

        for($n=0; $n<count($MonthlyRevenue); $n++)
        {
            $MontlyProfitValues[$n] = $MonthlyRevenue[$n]->total;
        }

        //Getting the average Revenue
        if(count($MontlyProfitValues) > 0){
            $AverageMonthlyRevenue = round(array_sum($MontlyProfitValues)/count($MontlyProfitValues));
        }

        if(count($MontlyProfitValues) > 0)
            $AverageDailyProfit = round((array_sum($MontlyProfitValues)-array_sum($MonthlyExpenseValues))/count($MontlyProfitValues));
        
        $TotalMonthlyOrders = array_sum($MonthlySalesValues);
        

        //Get Current Monthly Profit
        $CurrentMonthRevenue = DB::table('sales_h')
                                ->whereBetween('date', [$firstDayMonth, $lastDayMonth])
                                ->where('active', '=', 1)
                                ->sum('total');

        $CurrentMonthExpense = DB::table('purchase_h')
                                ->where('active', '=', 1)
                                ->whereBetween('date', [$firstDayMonth, $lastDayMonth])
                                ->sum('total');         
                                
        //Get the number of days Net Loss & Net Profit
        $NumberOfNetLossDays = 0;
        $NumberOfNetProfitDays = 0;
        
        if(count($MonthlyRevenue) == count($MonthlyExpenses)){
            for($s=0; $s<count($MonthlyRevenue); $s++)
            {
                ($MonthlyRevenue[$s]->total - $MonthlyExpenses[$s]->total) <= 0 ? $NumberOfNetLossDays++ : $NumberOfNetProfitDays++;
            }
        }

        $array = compact(
        'yesterday',
        'Inactive_Products', 
        'Active_Products', 
        'Deleted_Products',
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
        'MonthlySalesDays',
        'MonthlySalesValues', 
        'MonthStart', 
        'MonthEnd',
        'AverageMonthlyOrder',
        'CurrentMonthRevenue',
        'CurrentMonthExpense',
        'TotalMonthlyOrders',
        'CurrentMonth',
        'HighestMonthlySoldProducts', 
        'LowestMonthlySoldProducts',
        'HighestMonthlyExpense',
        'LowestMonthlyExpense',
        'AverageMonthlyExpense', 
        'AverageMonthlyRevenue',
        'AverageDailyProfit',
        'dateQuery', 
        'MonthlyRevenue',
        'MonthlyExpenses',
        'MonthlySoldProducts',
        'Date_LowestMonthlySoldProducts',
        'Date_HighestMonthlySoldProducts',
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

        $Expenses = PurchaseH::with(['user_modify'])
                            ->whereDate('date', '=', $dateQuery)
                            ->get();

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

        $Sales = SalesH::whereDate('date', '=',$dateQuery) 
                        ->get();

        for($x=0; $x<count($Sales); $x++)
        {
            $FinalSalesRecord[$x]['customer'] = $Sales[$x]->shop_name;
            $FinalSalesRecord[$x]['total_price'] = $Sales[$x]->total;

            $sales_d = DB::table('sales_d')
                        ->where('id_sales', '=', $Sales[$x]->id)
                        ->whereDate('date_order', '=', $dateQuery)
                        ->orderBy('id_sales', 'desc')
                        ->get();

            $currentProductName = "";
            $currentProductQuantity = "";

            for($y=0; $y<count($sales_d); $y++)
            {
                $FinalSalesRecord[$x]['quantity'] = $sales_d[$y]->total;
                
                $product = Product::find($sales_d[$y]->id_product);

                if($y != count($sales_d)-1){
                    $currentProductName .= $product->product_name. ", ";
                    $currentProductQuantity .= $sales_d[$y]->total. ", ";
                }else{
                    $currentProductName .= $product->product_name;
                    $currentProductQuantity .= $sales_d[$y]->total;
                }
                //dd($sales_d[$y]->total);
                $FinalSalesRecord[$x]['products'] = $currentProductName;
                $ProductsSold += $sales_d[$y]->total;
            }

            //dd($sales_d);
        }

        //dd($FinalSalesRecord);
        $SalesToday = DB::table('sales_h')->whereDate('date', '=', $dateQuery)->sum('total');
        
        $array = compact('dateQuery', 'FinalSalesRecord', 'SalesToday', 'ProductsSold');
        return view('/salesSummary', $array);
    }

    public function productsSummary()
    {
        $dateQuery = null; 

        if((isset($_GET["date_input"]) && (($_GET["date_input"])) !== '')){
            $dateQuery = $_GET["date_input"];
        }else{
            $dateQuery = date('yy-m-d');
        }

        $SalesToday = 0;

        $categories = Category::where('category_status', '=', '1')
                        ->orderBy("category_name", "desc")
                        ->get();

        $sales_d = SalesD::with(['product', 'sale'])
                    ->whereDate('date_order', '=',$dateQuery)
                    ->get();

        if(count($sales_d) > 0){
                        
            for($x=0; $x<count($categories); $x++)
            {
                for($i = 0; $i<count($sales_d); $i++)
                {
  
                    $currentProductCategory = Category::with(['product'], 'product_id', $sales_d[$i]->id_product)
                                                        ->where('category_id', '=', $sales_d[$i]->product->product_type)
                                                        ->first();
                                                        
                    if($currentProductCategory->category_id == $categories[$x]->category_id){

                        $categories[$x]['quantity'] += $sales_d[$i]->total;
                        //$categories[$x]['total'] += $sales_d[$i]->sale->total;
                        $categories[$x]['total'] += ($sales_d[$i]->price * $sales_d[$i]->total);
                        $categories[$x]['category'] = $currentProductCategory->category_name;
                    }

                }
            }

            $QtyToday = DB::table('sales_d')
                        ->whereDate('date_order', '=', $dateQuery)
                        ->sum('total');

            $SalesToday = DB::table('sales_h')
                            ->whereDate('date', '=', $dateQuery)
                            ->sum('total');

            $array = compact('categories', 'SalesToday', 'QtyToday', 'dateQuery');
            return view ('/productsSummary', $array);

        }else{
            $array = compact('dateQuery');
            return view ('/productsSummary', $array);
        }
    }

    public function RecentActivities()
    {
        $data = Activity::with('user', 
                                'product', 
                                'purchase_d', 
                                'purchase_h', 
                                'sales_d', 
                                'sales_h', 
                                'stock')
                            ->select('log_name', 'causer_id','description', 'subject_id', 'created_at')
                            ->orderBy('id', 'DESC')->take(1000)->get();

        
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
        })->editColumn('created_at', function($data){
            return date('d M Y - H:i:s', $data->created_at->timestamp);
        })->make(true);
    }

    public function percentageChangeCalculator($YesterdayValue, $TodayValue)
    {
        if($YesterdayValue > 0 && $TodayValue > 0){
            $DifferenceChange = $TodayValue - $YesterdayValue;
            $PercentageChange = round(($DifferenceChange / $TodayValue) * 100, 0);
        }else{
            $PercentageChange = null;
        }

        return abs($PercentageChange);
    }
}
