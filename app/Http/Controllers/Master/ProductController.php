<?php

namespace App\Http\Controllers\Master;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Model\Master\Product;
use Illuminate\Support\Facades\Validator;
use TJGazel\Toastr\Facades\Toastr;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use Illuminate\Support\Facades\Redirect;
use App\Model\Master\Category;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("product.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->where('category_status', '=', '1');
        $latestProductCode = intval(Product::select('product_code')
                                        ->orderBy('product_id', 'DESC')
                                        ->pluck('product_code')
                                        ->first()) + 1;
                                        
        return view("product.create", compact('categories', 'latestProductCode'));
    }

    public function store(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|unique:products|max:255',
            'product_name' => 'required|max:255',
        ]);

        if($validator->fails()){
            Toastr::warning('Product code cannot be repeated or blank.', 'Warning');
            return Redirect::back()->withErrors($validator)->withInput();
        } 

        if($request->stock_available > $request->stock_total)
        {
            Toastr::warning('Product available must not be higher than Product total.', 'Warning');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new Product();

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $imageName = $request->image->getClientOriginalName();
                $request->image->storeAs('images', $imageName, 'public');
                $data->product_image = $imageName;
            }
        }

        $data->product_code = $request->product_code;
        $data->product_name = $request->product_name;
        $data->product_type = $request->category_status;
        $data->product_brand = $request->product_brand;

        $data->stock_total = $request->stock_available/1;
        $data->stock_available = $request->stock_available/1;
        
        $data->purchase_price = $request->purchase_price/1;
        $data->product_selling_price = $request->product_selling_price/1;
        
        $data->product_information = $request->product_information;
        $data->product_active = $request->status;
        $data->user_modified = $request->user()->id;

        if($data->save()){
            Toastr::success('Product created Successfully', 'Success');
            return view('product.index');
        }else{
            Toastr::error('Failed to create new Product', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $data = Product::with(['user_modify'], 'id', $product->user_modified)
                        ->where('product_id', '=', $id)->first();
                        
        $category = Category::select('category_name')
                            ->where('category_id', '=', $data->product_type)
                            ->pluck('category_name')
                            ->first();

        if($data->count() > 0){
            return view('product.view', compact('data', 'category'));
        }else{
            Toastr::error('Product cannot be retrieved.', 'Error');
            return view('product.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all()->where('category_status', '=', '1');
        $data = Product::where('product_id', $id)->where('product_active', '!=', 2)->get();
        //dd($data);
        if($data->count() > 0){
            return view('product.update', compact('data', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = Product::find($id);
        if ($request->hasFile('image')){     
            if ($request->file('image')->isValid()) {   

                $imageName = $request->image->getClientOriginalName();
            
                //code for remove old file
                if($data->product_image != '' || $data->product_image != null){
                    Storage::delete('/public/images/'.$data->product_image);
                }

                $request->image->storeAs('images', $imageName, 'public');
                $data->product_image = $imageName;
            }
       }

        $data->product_code = $request->product_code;
        $data->product_name = $request->product_name;
        $data->product_type = $request->category_status;
        $data->product_brand = $request->product_brand;

        $data->stock_total = $request->stock_available/1;
        $data->stock_available = $request->stock_available/1;
        
        $data->purchase_price = $request->purchase_price/1;
        $data->product_selling_price = $request->product_selling_price/1;
        
        $data->product_information = $request->product_information;
        $data->product_active = $request->status;
        $data->user_modified = $request->user()->id;

        if($data->save()){
            Toastr::success('Product updated Successfully', 'Success');
            return view('product.index');
        }else{
            Toastr::error('Failed to update Product', 'Error');
            return redirect()->back();
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = Product::find($id);
        $data->product_active = 2;
        $data->user_modified = Auth::user()->id; //Fix this line, If user session is expired an ErrorException will appear in console

        if($data->save()){
            Toastr::success('Product Deleted successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Product cannot be Deleted.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function ProductActivities($id)
    {
        $data = Activity::with('user', 
                                'product', 
                                'sales_d', 
                                'sales_h', 
                                'stock',
                                'category')
                                ->where('subject_id', '=', $id)
                                ->where('log_name', '=', 'Product')
                                ->orderBy('created_at', 'desc')
                                ->get();

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
        })->editColumn('product_type', function($data){

            $retrieved_category = Category::find($data->product->product_type) ?? 'N\A';
            $category_name = $retrieved_category->category_name;
            return $category_name;
            
        })->make(true);
    }

    public function datatable()
    {
        $data = Product::where('product_active', '!=', 2);
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('master/product/'.$data->product_id.'/edit');
            $url = url('master/product/'.$data->product_id);
            $url_history = url('master/product/history/'.$data->product_id);
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            //$history = "<a class = 'btn btn-action btn-warning' href = '".$url_history."' title = 'History' data-toggle = 'modal' data-target = '#modal-default'>Purchase Detail</a>";
            
            return $view."".$edit."".$delete;

        })
        ->editColumn('product_selling_price', function($data){
            return number_format($data->product_selling_price, 0, '.', ',');
        })
        ->editColumn('stock_available', function($data){
            return number_format($data->stock_available, 0, '.', ',');
        })
        ->editColumn('product_type', function($data){

            $productCategory = Category::select('category_name')
                            ->where('category_id', '=', $data->product_type)
                            ->pluck('category_name')
                            ->first();
                            
            $string_replace = str_ireplace("\r\n", ',', $productCategory);
            return Str::limit($string_replace, 25, '...');
        })
        ->editColumn('product_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->product_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('product_code', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->product_code);
            return Str::limit($string_replace_name, 10, '...');
        })
        ->rawColumns(['action'])
        ->editColumn('product_id', 'ID:{{$product_id}}')
        ->make(true);
    }

    public function dataTableTrash()
    {
        $data = Product::where('product_active', '=', 2);
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('master/product/'.$data->product_id);
            $undoTrash = url('product/undoTrash/'.$data->product_id);
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-danger' title = 'Undo Delete'>Activate Product</button>";
            
            return $view."".$undo;

        })
        ->editColumn('purchase_price', function($data){
            return number_format($data->purchase_price, 0, '.', ',');
        })
        ->editColumn('product_selling_price', function($data){
            return number_format($data->product_selling_price, 0, '.', ',');
        })
        ->editColumn('product_name', function($data){
            $string_replace = str_ireplace("\r\n", ',', $data->product_name);
            return Str::limit($string_replace, 30, '...');
        }) 
        ->editColumn('product_type', function($data){

            $productCategory = Category::select('category_name')
                            ->where('category_id', '=', $data->product_type)
                            ->pluck('category_name')
                            ->first();
                            
            $string_replace = str_ireplace("\r\n", ',', $productCategory);
            return Str::limit($string_replace, 25, '...');
        })
        ->editColumn('stock_available', function($data){
            return number_format($data->stock_available, 0, '.', ',');
        })
        ->rawColumns(['action'])
        ->editColumn('product_id', 'ID:{{$product_id}}')
        ->make(true);
    }

    public function undoTrash($id){

        $data = Product::find($id);

        $data->product_active = 1;
        $data->user_modified = Auth::user()->id;
        
        if($data->save()){
            Toastr::success('Product has been activated successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Product cannot be activated.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function datatable_product(){
        $data = Product::select('products.*')->with(['product_category'])->where('products.product_active', '=', '1');
        
        return Datatables::of($data)
                ->editColumn('product_selling_price', function($data){
                    return intval($data->product_selling_price);
                }) 
                ->editColumn('product_name', function($data){
                    $string_replace = str_ireplace("\r\n", ',', $data->product_name);
                    return Str::limit($string_replace, 50, '...');
                })
                ->editColumn('product_type', function($data){

                    $category_name = Category::select('category_name')
                            ->where('category_id', '=', $data->product_type)
                            ->pluck('category_name')
                            ->first();

                    $string_replace = str_ireplace("\r\n", ',', $category_name);
                    return Str::limit($string_replace, 30, '...');
                })
                ->make(true);
    }

}
