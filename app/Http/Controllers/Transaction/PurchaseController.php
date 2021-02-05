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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseExport;
use App\Content;
use App\User;
use DB; 
use Carbon\Carbon;
use App\Quotation;

class PurchaseController extends Controller
{
    public function index()
    {
        $detail_count = 0;
        $currentDateTime = Carbon::now();
        $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());

        return view('Transaction.Purchase.index', compact('detail_count', 'invoice_no'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_invoice' => 'required|unique:purchase_h|max:255',
            'date' => 'required',
            'id_raw_product' => 'required',
            'name_raw_product' => 'required',
            'price' => 'required',
        ]);

        if($validator->fails()){
            toastr()->warning('Failed to create new Purchase Order. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new PurchaseH();
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->no_invoice = $request->no_invoice;
        $data->information = $request->product_information;
        $data->active = 1;
        $data->user_modified = Auth::user()->id; 

        $total = 0;

        if($data->save())
        {
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

            if($data->save())
            {
                $detail_count = 0;
                $currentDateTime = Carbon::now();
                $invoice_no = preg_replace('/[^0-9,.]/', '', $currentDateTime->toDateTimeString());

                toastr()->success('Purchase Order created successfully');
                return view('Transaction.Purchase.index', compact('detail_count', 'invoice_no'));
            }

            toastr()->error('Failed to create a new Purchase Order.');
            return redirect()->back();
        }
    }
     
    public function show($id)
    {
        $data = PurchaseH::with(['user_modify'])->find($id);

        $productDetail = PurchaseD::where('id_purchase', '=', $data->id)->orderBy('id', 'ASC')->get();

        $content = Content::first();

        if($data->count() <= 0 || $productDetail->count() <= 0)
        {
            toastr()->error('Purchase Order failed to retrieve data.');
            return redirect()->back();
        }

        return view('Transaction.Purchase.view', compact('data', 'productDetail', 'content'));
    }

    public function edit($id)
    {
        $detail_count = 0;
        $data = PurchaseH::find($id);

        $detail = PurchaseD::where('id_purchase', '=', $data->id)->orderBy('id', 'ASC')->get();

        if($data->count() <= 0 || $detail->count() <= 0)
        {
            toastr()->error('Purchase Order failed to retrieve data.');
            return redirect()->back();
        }
        
        return view('Transaction.Purchase.update', compact('data', 'detail', 'detail_count'));
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
            toastr()->warning('Failed to create new Purchase Order. Please check your inputs.');
            return redirect()->back();
        }
        
        $purchaseH = PurchaseH::find($id);
        
        $purchaseH->date = date('Y-m-d', strtotime($request->date));
        $purchaseH->no_invoice = $request->no_invoice;
        $purchaseH->information = $request->information;
        $purchaseH->user_modified = Auth::user()->id; 

        if(!$purchaseH->update())
        {
            toastr()->error('Purchase Order failed to update.');
            return redirect()->back();
        }

        foreach($_POST['name_raw_product'] as $key=>$name_raw_product){
            
            $detail = PurchaseD::where('id_purchase', $id)->where('product_name', '=', $name_raw_product)->first();

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
                $detail->id_purchase = $id;
                $detail->product_name = $name_raw_product;
                $detail->price = $_POST['price'][$key];
                $detail->save();
            }
        }

        //find purchase details that has been removed
        $toBeRemoved = PurchaseD::where('id_purchase', '=', $id)->whereNotIn('product_name', $_POST['name_raw_product'])->get();
        if(count($toBeRemoved) > 0)
        {
            for($x=0; $x<count($toBeRemoved); $x++)
            {
                $res = PurchaseD::where('id',$toBeRemoved[$x]->id)->delete();

                if(!$res)
                {
                    toastr()->error('A Purchase Item failed to be removed from the Purchase Order.');
                    return redirect()->back();
                }
            }
        }

        $purchaseH->total = $NewTotal;

        if($purchaseH->update())
        {
            toastr()->success('Purchase Order updated successfully.');
            return redirect()->route('purchase-order.index');
        }

        toastr()->error('Purchase Order failed to update.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = PurchaseH::find($id);
        $data->active = 0;
        $data->user_modified = Auth::user()->id; 

        if($data->save()){
            toastr()->success('Purchase Order successfully deactivated.');
            return new JsonResponse(['status'=>true]);
        }else{
            toastr()->error('Purchase Order failed to deactivate.');
            return new JsonResponse(['status'=>false, 'url'=> route('purchase-order.index')]);
        }
    }

    public function popup_media_product($id_count = null)
    {
        return view('Transaction.Purchase.view_product')->with('id_count', $id_count);
    }

    public function datatable()
    {
        $data = PurchaseH::with(['user_modify'])->where('active', 1)->get();

        return DataTables::of($data)
            ->addColumn('action', function($data){
                $url_edit = url('transaction/purchase-order/'.$data->id.'/edit');
                $url = url('transaction/purchase-order/'.$data->id);

                $view = "<a class = 'btn btn-action btn-primary' href = '".$url."' title = 'View'><i class = 'nav-icon fas fa-eye'></i></a>";
                $edit = "<a class = 'btn btn-action btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav-icon fas fa-edit'></i></a>";
                $delete = "<button data-url = '".$url."' onclick = 'deactivatePurchaseOrder(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
               
                if (Auth::user()->hasRole('A')) {
                    return $view."".$edit."".$delete;
                }else{
                    return $view;
                }
                
            })->editColumn('user_modified', function($data){

                $addedBy_ID = User::find($data->user_modified);
                $addedByFullName = $addedBy_ID->first_name. " ". $addedBy_ID->last_name;
                $string_replace_name = str_ireplace("\r\n", ',', $addedByFullName);

                return Str::limit($string_replace_name, 50, '...');
            })->editColumn('date', function($data){
                return date('d-m-Y', strtotime($data->date));
            })->editColumn('total', function($data){
                return number_format($data->total, 0, '.', ',');
            })->addColumn('checkbox', function($data){
                $chk = '<input type="checkbox" name="purchases_checkbox[]" class="purchases_checkbox" value="'. $data->id .'" />';
                return $chk;
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    public function dataTableTrash()
    {
        $data = PurchaseH::where('active', 0)->get();
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('transaction/purchase-order/'.$data->id);
            $undoTrash = url('purchase-order/undoTrash/'.$data->id);
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
            
            return $view."".$undo;

        })->editColumn('user_modified', function($data){

            $addedBy_ID = User::find($data->user_modified);
            $addedByFullName = $addedBy_ID->first_name. " ". $addedBy_ID->last_name;
            $string_replace_name = str_ireplace("\r\n", ',', $addedByFullName);
            
            return Str::limit($string_replace_name, 50, '...');
        })->editColumn('date', function($data){
            return date('d-m-Y', strtotime($data->date));
        })->editColumn('total', function($data){
            return number_format($data->total, 0, '.', ',');
        })->addColumn('checkbox_t', function($data){
            $chk = '<input type="checkbox" name="purchases_trash_checkbox[]" class="purchases_trash_checkbox" value="'. $data->id .'" />';
            return $chk;
        })
        ->rawColumns(['checkbox_t', 'action'])
        ->make(true);
    }

    public function undoTrash($id){

        $data = PurchaseH::find($id);

        $data->active = 1;
        $data->user_modified = Auth::user()->id;
       
        if($data->save()){
            toastr()->success('Purchase Order successfully activated.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Purchase Order failed to re-activate.');
        return new JsonResponse(['status'=>false]);
    }
    
    public function print($id)
    {
        $data = PurchaseH::with(['user_modify'])->find($id);

        if($data->count() <= 0)
        {
            toastr()->error('Purchase Order failed to retrieve data.');
            return redirect()->back();
        }

        $detail = PurchaseD::with('purchase')->where('id_purchase', $data->id)->orderBy('id', 'ASC')->get();
        $content = Content::first();

        return view('Transaction.Purchase.print', compact('data', 'detail', 'content'));
    }

    public function purchasesExport() 
    {
        return Excel::download(new PurchaseExport, 'purchases-report.xlsx');
    }   

    public function deactivatePurchase(Request $request)
    {
        $ids = $request->input('id');
        $sales = PurchaseH::whereIn('id', $ids)->update(array('active' => 0, 'user_modified' => Auth::user()->id));
  
        echo 'Sales Order/s successfully deactivated.';
    }

    public function reactivatePurchase(Request $request)
    {
        $ids = $request->input('id');
        $sales = PurchaseH::whereIn('id', $ids)->update(array('active' => 1, 'user_modified' => Auth::user()->id));
  
        echo 'Sales Order/s successfully re-activated.';
    }
}

