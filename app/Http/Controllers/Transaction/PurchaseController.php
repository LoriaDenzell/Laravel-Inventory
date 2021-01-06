<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use App\Model\Master\Product;
use App\Model\Transaction\Stock;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use DB;
use Carbon;
use App\Quotation;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('Transaction.Purchase.index');
    }

    public function create()
    {
        $detail_count = 0;
        $currentDateTime = Carbon\Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());
        return view('Transaction.Purchase.create', compact('detail_count', 'invoice_no'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_invoice' => 'required|unique:purchase_h|max:255',
        ]);

        if($validator->fails()){
            Toastr::warning('Invoice No. cannot be repeated or blank.', 'Warning');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new PurchaseH();
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->no_invoice = $request->no_invoice;
        $data->information = $request->product_information;
        $data->status = $request->purchase_status;
        $data->active = 1;
        $data->user_modified = Auth::user()->id; 

        $total = 0;

        if($data->save()){
            $id_purchase = $data->id;

            if(isset($_POST['name_raw_product'])){
                foreach($_POST['name_raw_product'] as $key=>$name_raw_product):

                    $detail = new PurchaseD();
                    $detail->id_purchase = $data->id;
                    $detail->product_name = Str::upper($name_raw_product);
                    $detail->price = $_POST['price'][$key];
                    $total = $total + $detail->price;

                    $detail->save();

                endforeach;
            }

            $data = PurchaseH::find($id_purchase);
            $data->total = $total;

            if($data->save()){
                Toastr::success('Purchase order saved successfully.', 'Success');
                //return view('purchase-order.index');
                return view('Transaction.Purchase.index');
            }else{
                Toastr::error('Failed to save purchase order.', 'Error');
                return redirect()->back();
            }
        }
    }
    
    public function show($id)
    {
        $data = PurchaseH::with(['user_modify'])->where('id', $id)->get();
        
        if($data->count() > 0){
            $productDetail = PurchaseD::where('id_purchase', '=', $data[0]->id)
                                ->orderBy('id', 'ASC')
                                ->get();
                                
            return view('Transaction.Purchase.view', compact('data', 'productDetail'));
        }
    }

    public function edit($id)
    {
        $detail_count = 0;

        $data = PurchaseH::where('id', $id)
                            ->where('active', '!=', '2')
                            ->get();
                            
        if($data->count() > 0){

            $detail = PurchaseD::where('id_purchase', '=', $data[0]->id)
                                ->orderBy('id', 'ASC')
                                ->get();
                                
            if($detail->count() > 0){
                return view('Transaction.Purchase.update', compact('data', 'detail', 'detail_count'));
            }else{
                Toastr::error('Cannot retrieve product data.', 'Error');
                return redirect()->back();
            }
                
        }else{
            Toastr::error('Cannot retrieve Purchase data.', 'Error');
            return redirect()->back();
        }
       
    }

    public function update(Request $request, $id)
    {
        $NewTotal = 0;
        
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'no_invoice' => 'required',
            'name_raw_product' => 'required|array|min:1',
            'price' => 'required',
        ]);

        if($validator->fails()){
            Toastr::error('Please check if all required fields are filled before submitting.', 'Error');
            return redirect()->back();
        }
        
        $purchaseH = PurchaseH::find($id);
        
        $purchaseH->date = date('Y-m-d', strtotime($request->date));
        $purchaseH->no_invoice = $request->no_invoice;
        $purchaseH->information = $request->information;
        $purchaseH->user_modified = Auth::user()->id; 

        if($purchaseH->update()){

            foreach($_POST['name_raw_product'] as $key=>$name_raw_product){
             
                $detail = PurchaseD::where('id_purchase', '=', $id)
                                    ->where('product_name', '=', $name_raw_product)
                                    ->first();

                $NewTotal += $_POST['price'][$key];

                //if record exists already, then update
                if($detail != null)
                {
                    
                    $detail->product_name = $name_raw_product;
                    $detail->price = $_POST['price'][$key];
                    $detail->update();
                }
                else //If no record exists, create a new one (new item added)
                {
                    $detail = new PurchaseD();
                    $detail->id_purchase = $data->id;
                    $detail->product_name = $name_raw_product;
                    $detail->price = $_POST['price'][$key];
                    $detail->save();
                }
            }

            //find purchase details that has been removed
            $toBeRemoved = PurchaseD::where('id_purchase', '=', $id)->whereNotIn('product_name', $_POST['name_raw_product'])->get();
            if(count($toBeRemoved) > 0){
                for($x=0; $x<count($toBeRemoved); $x++){
                    
                    $res = PurchaseD::where('id',$toBeRemoved[$x]->id)->delete();

                    if(!$res){
                        Toastr::error('A Purchase failed to be removed from the Purchase Order.', 'Error');
                        return redirect()->back();
                    }
                }
            }

            $purchaseH->total = $NewTotal;

            if($purchaseH->update()){
                Toastr::success('Purchase order successfully updated.', 'Success');
                return redirect()->route('purchase-order.index');
            }

            Toastr::error('Failed to updated the Purchase Order', 'Error');
            return redirect()->back();

        }

        Toastr::error('Failed to updated the Purchase Order', 'Error');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = PurchaseH::find($id);
        $data->active = 2;
        $data->user_modified = Auth::user()->id; 

        if($data->save()){
            Toastr::success('Purchase Order successfully deleted.', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Purchase Order failed to delete.', 'Error');
            return new JsonResponse(['status'=>false, 'url'=> route('purchase-order.index')]);
        }
    }

    public function popup_media_product($id_count = null){
        return view('Transaction.Purchase.view_product')->with('id_count', $id_count);
    }

    public function datatable(){

        $data = PurchaseH::where('active', '=', 1);

        return DataTables::of($data)
            ->addColumn('action', function($data){
                $url_edit = url('transaction/purchase-order/'.$data->id.'/edit');
                $url = url('transaction/purchase-order/'.$data->id);

                $view = "<a class = 'btn btn-action btn-primary' href = '".$url."' title = 'View'><i class = 'nav-icon fas fa-eye'></i></a>";
                $edit = "<a class = 'btn btn-action btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav-icon fas fa-edit'></i></a>";
                $delete = "<button data-url = '".$url."' onclick = 'deletePurchaseOrder(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
               
                if (Auth::user()->hasRole('A')) {
                    return $view."".$edit."".$delete;
                }else{
                    return $view;
                }
                
                return $view;
            
            })->editColumn('date', function($data){
                return date('d-m-Y', strtotime($data->date));
            })->editColumn('total', function($data){
                return number_format($data->total, 0, '.', ',');
            })
            ->make(true);
    }

    public function dataTableTrash()
    {
        $data = PurchaseH::where('active', '=', 2);
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('transaction/purchase-order/'.$data->id);
            $undoTrash = url('purchase-order/undoTrash/'.$data->id);
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-danger' title = 'Undo Delete'>Activate Purchase</button>";
            
            return $view."".$undo;

        })->editColumn('date', function($data){
            return date('d-m-Y', strtotime($data->date));
        })->editColumn('total', function($data){
            return number_format($data->total, 0, '.', ',');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function undoTrash($id){

        $data = PurchaseH::find($id);

        $data->active = 1;
        $data->user_modified = Auth::user()->id;
       
        if($data->save()){
            Toastr::success('Purchase Order has been activated successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Purchase Order cannot be activated.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function received(Request $request, $id){

        $dataH = PurchaseH::find($id);
        $data = PurchaseD::where('id_purchase', '=', $id)->orderBy('id', 'ASC')->get();

        foreach($data as $data){

            $detail = new Stock();
            $detail->id_product = $data->id_product;
            $detail->total = $data->total; 
            $detail->information = $dataH->no_invoice;
            $detail->type = "buy";
            $detail->save();

            $detail = Product::find($data->id_product);
            $detail->purchase_price = $data->price; 
            $detail->stock_total = $detail->stock_total + $data->total;
            $detail->stock_available = $detail->stock_available + $data->total;

            $detail->save();
        }

        $data = PurchaseH::find($id);
        $data->status = 'received';
        $data->user_modified = Auth::user()->id;

        if($data->save()){
            Toastr::success('Purchase order received Successfully.', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Purchase order cannot be "received".', 'Error');
            return new JsonResponse(['status'=>false]);
        } 
    }

    public function print($id){
        
        $data = PurchaseH::with(['user_modify'])->where('id', $id)->get();

        if($data->count() > 0){
            $detail = PurchaseD::with('product')
                            ->where('id_purchase', '=', $data[0]->id)
                            ->orderBy('id', 'ASC')
                            ->get();

            return view('Transaction.Purchase.print', compact('data', 'detail'));
        }
    }
}

