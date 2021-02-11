<?php

namespace App\Http\Controllers\Transaction;

use App\Model\Transaction\Sales\SaleAddon;
use App\Model\Transaction\Sales\SalesD;
use App\Model\Transaction\Sales\SalesH;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use App\Model\Transaction\Stock;
use App\Model\Master\Product;
use App\Model\Master\Addon;
use App\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\SalesExport;

use Redirect;
use Auth;
use Str; 
use Carbon;
use DB;

class SaleController extends Controller
{
    public function index()
    {
        $detail_count = 0;
        $currentDateTime = Carbon\Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());
        $addons = Addon::orderBy('addon_name')->where('addon_active', 1)->get() ?? null;

        return view('Transaction.Sales.index', compact('detail_count', 'invoice_no', 'addons'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'invoice_no' => 'required|unique:sales_h|max:255',
            'id_raw_product' => 'required',
            'name_raw_product' => 'required',
            'price' => 'required',
            'total' => 'required',
        ]);

        //If validation and products are empty
        if($validator->fails() || $_POST['id_raw_product'][0] == null)
        {
            toastr()->warning('Failed to create new Sales Order. Please check your inputs.');
            return redirect()->back()->withInput($request->all);
        }

        //Checking the stocks available for each product before proceeding
        for($i=0; $i<count($request['id_raw_product']); $i++)
        {
            $product = Product::find($_POST['id_raw_product'][$i]);
            $currentTotal = str_replace( ',', '', $request['total'][$i]);
            $currentAvailable = str_replace( ',', '', $product->stock_available);

            if($currentTotal > $currentAvailable)
            {
                toastr()->warning("One of the products has insufficient stock.");
                return redirect()->back()->withInput();
            }
        }

        //SalesH
        $data = new SalesH();
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->invoice_no = $request->invoice_no;
        $data->customer = $request->customer == "" ? "N/A" : Str::upper($request->customer);
        $data->information = $request->product_information;
        $data->active = 1;
        $data->user_modified = Auth::user()->id; 
        $total = 0;

        if(!$data->save())
        {
            toastr()->error("Sales Order failed to create.");
            return redirect()->back()->withInput();
        }

        $id_sales = $data->id;

        //SalesD
        foreach($_POST['id_raw_product'] as $key=>$id_raw_product):

            $detail = new SalesD();
            $detail->id_sales = $data->id;
            $detail->id_product = $id_raw_product;
            $detail->date_order = date('Y-m-d', strtotime($request->date));
            $detail->total = str_replace( ',', '', $_POST['total'][$key]);
            $detail->price = str_replace( ',', '', $_POST['price'][$key]);
            $total = $total + ($detail->total * $detail->price);

            $detail->save();

        endforeach;

        //Addons
        if(array_sum($_POST['addon_total']) > 0){
            
            for($i=0; $i<count($_POST['addons']); $i++){
                
                $addon = new SaleAddon();
                $addon_price = Addon::select('addon_cost')->where('id', $_POST['addons'][$i])->first();

                if($addon_price == null)
                {
                    toastr()->error("Product Addon failed to retrieve data.");
                    return redirect()->back()->withInput();
                }

                $addon->addon_id = $_POST['addons'][$i];
                $addon->sales_h = $data->id;
                $addon->total_addon = $_POST['addon_total'][$i] == "" ? 1 : $_POST['addon_total'][$i];
                $addon->price = (int)$addon_price->addon_cost * $addon->total_addon;
                $total = $total + $addon->price;
                $addon->save();
            }
        }

        $data = SalesH::find($id_sales);
        $data->total = $total;
        $data->save();

        $dataH = SalesH::find($id_sales);
        $data = SalesD::where('id_sales', $id_sales)->orderBy('id', 'ASC')->get();

        if($data->count() <= 0)
        {
            toastr()->warning("Product failed to retrieve data.");
            return redirect()->back()->withInput($request->all);
        }

        //Stocks
        foreach($data as $data)
        {
            $detail = new Stock();
            $detail->id_product = $data->id_product;
            $detail->information = $dataH->invoice_no;
            $detail->total = $data->total*-1;
            $detail->type = "sell";
            $detail->save();

            $productDetail = Product::find($data->id_product);
            $productDetail->stock_available = $productDetail->stock_available - $data->total;

            $productDetail->save();
        }

        $detail_count = 0;
        $currentDateTime = Carbon\Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());
        $addons = Addon::orderBy('addon_name')->where('addon_active', '=', 1)->get();
        
        toastr()->success('Sales Order created successfully');
        return view('Transaction.Sales.index', compact('detail_count', 'invoice_no', 'addons'));
    }

    public function show($id)
    {
        $data = SalesH::with(['user_modify'])->find($id);

        $detail = SalesD::with(['product'], 'product_id')->where('id_sales', $data->id)->orderBy('id', 'ASC')
                            ->get();

        $addons = SaleAddon::with(['addon'], 'addon_id')->where('sales_h', $data->id)->get();

        $content = Content::first();

        if($data->count() <= 0)
        {
            toastr()->error("Sales Order failed to retrieve data.");
            return view('Transaction.Sales.index');
        }

        return view('Transaction.Sales.view', compact('data', 'detail', 'addons', 'content'));
    }

    public function edit($id)
    {
        $detail_count = 0;
        $salesH = SalesH::find($id);

        $salesD = SalesD::where('id_sales', $salesH->id)->with('product')->orderBy('id_sales', 'ASC')->get();

        $addons = Addon::orderBy('addon_name')->where('addon_active', 1)->get();
        $sales_addons = SaleAddon::where('sales_h', $id)->get();
      
        if($salesH->count() <= 0 || $salesD->count() <= 0){
            toastr()->error("Sales Order failed to retrieve data."); 
            return view('Transaction.Sales.index');
        }

        return view('Transaction.Sales.update', compact('detail_count','salesH', 'salesD', 'addons', 'sales_addons'));  
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'id_raw_product' => 'required|array|min:1',
            'id_raw_product' => 'required',
            'name_raw_product' => 'required',
            'price' => 'required',
            'total' => 'required',
        ]);
        
        //If validation and products are empty
        if($validator->fails() || $_POST['id_raw_product'][0] == null)
        {
            toastr()->warning('Failed to create new Sales Order. Please check your inputs.');
            return redirect()->back();
        }

        //Checking the stocks available for each product before proceeding
        for($i=0; $i<count($request['id_raw_product']); $i++){
            
            $product = Product::find($_POST['id_raw_product'][$i]);
            $currentTotal = str_replace( ',', '', $request['total'][$i]);
            $currentAvailable = str_replace( ',', '', $product->stock_available);

            if($currentTotal > $currentAvailable)
            {
                toastr()->warning("One of the products has insufficient stock.");
                return redirect()->back()->withInput();
            }
        }

        //SalesH
        $data = SalesH::find($id);
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->invoice_no = $request->invoice_no;
        $data->information = $request->information;
        $data->customer = $request->customer;
        $data->user_modified = Auth::user()->id; 

        $total = 0;

        if(!$data->save()){
            toastr()->error('Sales Order failed to update', 'Error');
            return redirect()->back()->withInput();
        }

        $delete = SalesD::where('id_sales', $id)->delete();

        //SalesD
        foreach($_POST['id_raw_product'] as $key=>$id_raw_product):

            $detail = new SalesD();
            $detail->id_sales = $id;
            $detail->id_product = $id_raw_product;
            $detail->date_order = $data->date;
            $detail->total = $_POST['total'][$key];
            $detail->price = $_POST['price'][$key];
            $total = $total + ($detail->total * $detail->price);

            $detail->save();

        endforeach;
        
        //Addons
        if(isset($_POST['addons'])){
            for($i=0; $i<count($_POST['addons']); $i++){

                $delete = SaleAddon::where('sales_h', $id)->delete();
                $addon = new SaleAddon();

                $addon_price = Addon::select('addon_cost')->where('id', $_POST['addons'][$i])->first();

                if($addon_price == null)
                {
                    toastr()->error('Product Addon failed to update', 'Error');
                    return redirect()->back()->withInput();
                }

                //if Addon ID is null then skip to the next
                if($_POST['addons'][$i] == "")
                    continue;
                
                $addon->addon_id = $_POST['addons'][$i];
                $addon->sales_h = $data->id;
                $addon->total_addon = $_POST['addon_total'][$i];
                $addon->price = (int)$addon_price->addon_cost * (int)$_POST['addon_total'][$i];
                $total = $total + $addon->price;
                $addon->save();
            }
        }

        //SalesH - total
        $data = SalesH::find($id);
        $data->total = $total;
        $data->save();

        $dataH = SalesH::find($id);
        $dataD = SalesD::where('id_sales', $dataH->id)->orderBy('id', 'ASC')->get();

        if($dataD->count() <= 0){
            toastr()->error('Sales Order failed to retrieve data.', 'Error');
            return redirect()->back()->withInput($request->all);
        }

        //Stocks
        foreach($dataD as $data){
            $detail = new Stock();
            $detail->id_product = $data->id_product;
            $detail->information = $dataH->invoice_no;
            $detail->total = $data->total*-1;
            $detail->save();

            $productDetail = Product::find($data->id_product);
            $productDetail->stock_available = $productDetail->stock_available - $data->total;

            $productDetail->save();
        }

        toastr()->success('Sales Order updated successfully.');
        $detail_count = 0;
        $currentDateTime = Carbon\Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());
        $addons = Addon::orderBy('addon_name')->where('addon_active', '=', 1)->get() ?? null;

        return view('Transaction.Sales.index', compact('detail_count', 'invoice_no', 'addons'));
    }

    public function deactivate($id)
    {
        $data = SalesH::find($id);
        $data->active = 0;
        $data->user_modified = Auth::user()->id; 

        if($data->save())
        {
            toastr()->success('Sales Order successfully deactivated.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Sales Order failed to deactivate.');
        return new JsonResponse(['status'=>false, 'url'=> route('sales.index')]);
    }

    public function popup_media_product($id_count = null)
    {
        return view('Transaction.Sales.view_product')->with('id_count', $id_count);
    }

    public function datatable()
    {
        $data = SalesH::where('active', 1)->orderBy('date', 'DESC')->get();

        return DataTables::of($data)
            ->addColumn('action', function($data){
                $url = url('transaction/sales/'.$data->id);
                $url_deact = url('sales/deactivate/'.$data->id);
                $url_edit = url('transaction/sales/'.$data->id.'/edit');
                
                $view = "<a class = 'btn btn-action btn-primary' href = '".$url."' title = 'View'><i class = 'nav-icon fas fa-eye'></i></a>";
                $edit = "<a class = 'btn btn-action btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav-icon fas fa-edit'></i></a>";
                $deactivate = "<button data-url = '".$url_deact."' onclick = 'deactivateSalesData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
                
                if (Auth::user()->hasRole('A')) {
                    return $view."".$edit."".$deactivate;
                }else{ 
                    return $view;
                }
            })
            ->editColumn('date', function($data){
                return date('d-m-Y', strtotime($data->date));
            })
            ->editColumn('total', function($data){
                return number_format($data->total, 0, '.', ',');
            })
            ->addColumn('checkbox', function($data){
                $chk = '<input type="checkbox" name="sales_checkbox[]" class="sales_checkbox" value="'. $data->id .'" />';
                return $chk;
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    public function dataTableTrash()
    {
        $data = SalesH::where('active', 0)->orderBy('date', 'DESC')->get();
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('transaction/sales/'.$data->id);
            $undoTrash = url('sales/undoTrash/'.$data->id);
            $urlDestroy = url('sales/deleteSalesOrder');

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
      
            return $view."".$undo;

        })->editColumn('date', function($data){
            return date('d-m-Y', strtotime($data->date));
        })->editColumn('total', function($data){
            return number_format($data->total, 0, '.', ',');
        })->addColumn('checkbox_t', function($data){
            $chk_t = '<input type="checkbox" name="sales_trash_checkbox[]" class="sales_trash_checkbox" value="'. $data->id .'" />';
            return $chk_t;
        })
        ->rawColumns(['checkbox_t', 'action'])
        ->make(true);
    }

    public function undoTrash($id)
    {
        $data = SalesH::find($id);

        $data->active = 1;
        $data->user_modified = Auth::user()->id;
       
        if($data->save())
        {
            toastr()->success('Sales Order successfully reactivated.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Sales Order failed to deactivate.');
        return new JsonResponse(['status'=>false]);
    }

    public function print($id)
    {
        $data = SalesH::with(['user_modify'])->where('id', $id)->get();
        $detail = SalesD::with('product')->where('id_sales', $data[0]->id)->orderBy('id', 'ASC')->get();
        $addons = SaleAddon::with(['addon'], 'addon_id')->where('sales_h', $data[0]->id)->get();
        $content = Content::first();

        if(($data->count() > 0) && ($detail->count() > 0)){
            return view('Transaction.Sales.print', compact('data', 'detail', 'addons', 'content'));
        }

        toastr()->error('Sales Order failed to retrieve data.');
        return redirect()->back();
    }

    public function salesExport() 
    {
        return Excel::download(new SalesExport, 'sales-report.xlsx');
    }   

    public function deactivateSales(Request $request)
    {
        $ids = $request->input('id');
        $sales = SalesH::whereIn('id', $ids)->update(array('active' => 0, 'user_modified' => Auth::user()->id));
  
        echo 'Sales Order/s successfully deactivated.';
    }

    public function reactivateSales(Request $request)
    {
        $ids = $request->input('id');
        $sales = SalesH::whereIn('id', $ids)->update(array('active' => 1, 'user_modified' => Auth::user()->id));
  
        echo 'Sales Order/s successfully reaactivated.';
    }

}
