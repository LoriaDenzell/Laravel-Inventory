<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use App\Model\Transaction\Sales\SalesD;
use App\Model\Transaction\Sales\SalesH;
use App\Model\Master\Product;
use App\Model\Transaction\Stock;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Redirect;
use Auth;
use Str;
use Carbon;
use DB;

class SaleController extends Controller
{
    public function index()
    {
        return view('Transaction.Sales.index');
    }

    public function create()
    {
        $detail_count = 0;
        $currentDateTime = Carbon\Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());
        
        return view('Transaction.Sales.create', compact('detail_count', 'invoice_no'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required|unique:sales_h|max:255',
        ]);

        if($validator->fails()){
            
            Toastr::warning('Invoice No. cannot be repeated or blank.', 'Warning');
            //return redirect()->back()->withInput()->withErrors($validator->messages()->all());
            return redirect()->back()->withInput($request->all);
        }

        if(isset($_POST['id_raw_product'])){
            for($i=0; $i<count($request['id_raw_product']); $i++){
                
                $product = Product::find($_POST['id_raw_product'][$i]);
                $currentTotal = str_replace( ',', '', $request['total'][$i]);
                $currentAvailable = str_replace( ',', '', $product->stock_available);

                if($currentTotal > $currentAvailable)
                {
                    $error_message = "One of the products has less than stock available than the Inputted stock. Inputted stock should not exceed the available stock.";
                    Toastr::error($error_message, 'Error');
                    return redirect()->back()->withInput();
                    break;
                }
            }
        }

        $data = new SalesH();
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->invoice_no = $request->invoice_no;
        $data->shop_name = Str::upper($request->shop_name);
        $data->information = $request->product_information;
        $data->active = 1;
        $data->user_modified = Auth::user()->id; 
        $total = 0;

        if($data->save()){
            $id_sales = $data->id;

            if(isset($_POST['id_raw_product'])){
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
            }

            $data = SalesH::find($id_sales);
            $data->total = $total;
            $data->save();

            $dataH = SalesH::find($id_sales);
            $data = SalesD::where('id_sales', '=', $id_sales)->orderBy('id', 'ASC')->get();

            if($data->count() > 0){
 
                foreach($data as $data){
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
            }else{
                Toastr::warning('Product Information failed to retrieve. Please try again later.', 'Warning');
                return redirect()->back()->withInput($request->all);
            }
            
            Toastr::success('Sales Order created successfully.', 'Success');
            return view('Transaction.Sales.index');
        }

        Toastr::error('Sales Order cannot be created.', 'Error'); 
        return view('Transaction.Sales.index');
    }

    public function show($id)
    {
        $data = SalesH::with(['user_modify'])->where('id', $id)->get();

        if($data->count() > 0){

            $detail = SalesD::with(['product'], 'product_id')
                                ->where('id_sales', '=', $data[0]->id)
                                ->orderBy('id', 'ASC')
                                ->get();

            return view('Transaction.Sales.view', compact('data', 'detail'));
        }else{
            Toastr::error('Sales Order cannot be retrieved.', 'Error'); 
            return view('Transaction.Sales.index');
        }
    }

    public function edit($id)
    {
        $salesH = SalesH::where('id', $id)
                ->first();

        if($salesH->count() > 0){

            $salesD = SalesD::where('id_sales', $salesH->id)
                        ->with('product')
                        ->orderBy('id_sales', 'ASC')
                        ->get();

            if($salesD->count() > 0){
                return view('Transaction.Sales.update', compact('salesH', 'salesD'));
            }
        }else{
            Toastr::error('Sales Order cannot be retrieved.', 'Error'); 
            return view('Transaction.Sales.index');
        }
    }

    public function update(Request $request, $id)
    {
      
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'shop_name' => 'required',
            'id_raw_product' => 'required|array|min:1',
            'price' => 'required',
            'total' => 'required',
        ]);
        
        if($validator->fails()){
            Toastr::error('Please check if all required fields are filled before submitting.', 'Error');
            return redirect()->back();
        }

        $data = SalesH::find($id);
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->invoice_no = $request->invoice_no;
        $data->information = $request->information;
        $data->shop_name = $request->shop_name;
        $data->user_modified = Auth::user()->id; 

        $total = 0;

        if($data->save()){
            $delete = SalesD::where('id_sales', '=', $id)->delete();

            if(isset($_POST['id_raw_product'])){
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
            }

            $data = SalesH::find($id);
            $data->total = $total;

            $data->save();
            Toastr::success('Sales order updated successfully.', 'Success');
            return view('Transaction.Sales.index');
        }

        Toastr::error('Sales order cannot be updated.', 'Error');
        return redirect()->back()->withInput();
    }

    public function deleteSalesOrder($id)
    {
         $data = SalesH::find($id);
         $data->delete();
 
         if($data->save()){
             Toastr::success('Sales Order has been permanently deleted.', 'Success');
             return new JsonResponse(['status'=>true]);
             //return view('Transaction.Sales.index');
         }else{
             Toastr::error('Sales Order failed to be deleted.', 'Error');
             return new JsonResponse(['status'=>false, 'url'=> route('sales.index')]);
             //return view('Transaction.Sales.index');
         }
    }

    public function deactivate($id){
        $data = SalesH::find($id);
        $data->active = 2;
        $data->user_modified = Auth::user()->id; 

        if($data->save()){
            Toastr::success('Sales Order Deleted successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Sales Order cannot be Deleted.', 'Error');
            return new JsonResponse(['status'=>false, 'url'=> route('sales.index')]);
        }
    }

    public function popup_media_product($id_count = null){
        return view('Transaction.Sales.view_product')->with('id_count', $id_count);
    }

    public function datatable(){
        $data = SalesH::select('sales_h.*')
                        ->where('sales_h.active', '=', 1)
                        ->orderBy('date', 'DESC');

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
            ->make(true);
    }

    public function dataTableTrash()
    {
        $data = SalesH::where('active', '=', 2);
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('transaction/sales/'.$data->id);
            $undoTrash = url('sales/undoTrash/'.$data->id);
            $urlDestroy = url('sales/deleteSalesOrder/'.$data->id);

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
            $delete = "<form action='$urlDestroy' class = 'deleteForm' method='post'>
                            <div class='form-group'>
                                <input type='submit' class='btn btn-danger delete-user' value='Delete'>
                            </div>
                        </form>";
                        
            return $view."".$undo."".$delete;

        })->editColumn('date', function($data){
            return date('d-m-Y', strtotime($data->date));
        })->editColumn('total', function($data){
            return number_format($data->total, 0, '.', ',');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function undoTrash($id){

        $data = SalesH::find($id);

        $data->active = 1;
        $data->user_modified = Auth::user()->id;
       
        if($data->save()){
            Toastr::success('Sales Order has been activated successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Sales Order cannot be activated.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function print($id){

        $data = SalesH::with(['user_modify'])->where('id', $id)->get();

        if($data->count() > 0){
            $detail = SalesD::with('product')
                            ->where('id_sales', '=', $data[0]->id)
                            ->orderBy('id', 'ASC')
                            ->get();

            return view('Transaction.Sales.print', compact('data', 'detail'));
        }
    }
}
