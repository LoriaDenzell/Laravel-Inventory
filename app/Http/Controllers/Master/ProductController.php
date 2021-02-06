<?php

namespace App\Http\Controllers\Master;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use App\Model\Purchase\PurchaseD;
use App\Model\Purchase\PurchaseH;
use Yajra\DataTables\DataTables;
use App\Exports\ProductsExport;
use App\Model\Master\Category;
use App\Model\Master\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Content;
use DB;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all()->where('category_status', '=', '1');
        $latestProductCode = intval(Product::select('product_code')->orderBy('product_id', 'DESC')->pluck('product_code')
                                        ->first()) + 1;

        return view("product.index", compact('categories', 'latestProductCode'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|unique:products',
            'product_name' => 'required',
            'status' => 'required', 
            'product_selling_price' => 'required',
            'purchase_price' => 'required',
            'stock_available' => 'required',
            'stock_total' => 'required',
            'category_status' => 'required'
        ]);

        if($validator->fails())
        {
            toastr()->warning('Failed to create new Product. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        } 

        if($request->stock_available > $request->stock_total)
        {
            toastr()->warning('Product Stock Availabke must not be higher than Product Stock total.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new Product();
       
        if ($request->hasFile('image')) 
        {
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images'), $imageName);  
            $data->product_image = $imageName;
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
            $categories = Category::all()->where('category_status', '=', '1');
            $latestProductCode = intval(Product::select('product_code')->orderBy('product_id', 'DESC')->pluck('product_code')
                                        ->first()) + 1;

            toastr()->success('Product created successfully.');
            return view('product.index', compact('latestProductCode', 'categories'));
        }
        
        toastr()->success('Failed to create a new Product');
        return redirect()->back();
    }

    public function show($id)
    {
        $data = Product::with(['user_modify'])->where('product_id', $id)->first();
                        
        $category = Category::select('category_name')
                            ->where('category_id', '=', $data->product_type)
                            ->pluck('category_name')
                            ->first();

        if($data->count() < 0){
            toastr()->error('Product failed to retrieve data.');
            return view('product.index');
        }

        return view('product.view', compact('data', 'category'));
    }

    public function edit($id)
    {
        $categories = Category::all()->where('category_status', '1');
        $data = Product::find($id);
        
        if($data->count() < 0){
            toastr()->error('Product failed to retrieve data.');
            return view('product.index');
        }

        return view('product.update', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $data = Product::find($id);

        if($data == null){
            toastr()->error('Product failed to retrieve data.');
            return view('product.index');
        }

        if ($request->hasFile('image')){     
            if ($request->file('image')->isValid()) {   

                $imageName = $request->image->getClientOriginalName();
            
                if($data->product_image != '' || $data->product_image != null){
                    Storage::delete('/public/storage/images/'.$data->product_image);
                }

                $request->image->move(public_path('storage/images'), $imageName);  
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
            $categories = Category::all()->where('category_status', '1');
            $latestProductCode = intval(Product::select('product_code')
                                        ->orderBy('product_id', 'DESC')
                                        ->pluck('product_code')
                                        ->first()) + 1;

            toastr()->success('Product updated successfully');
            return view('product.index', compact('categories', 'latestProductCode'));
        }
        
        toastr()->error('Product failed to update data.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = Product::find($id);

        if($data == null){
            toastr()->error('Product failed to retrieve data.');
            return view('product.index');
        }

        $data->product_active = 0;
        $data->user_modified = Auth::user()->id; 

        if($data->save()){
            toastr()->success('Product Deleted deactivated successfully.', 'Success');
            return new JsonResponse(['status'=>true]);
        }

        toastr()->error('Product failed to deactivate.');
        return new JsonResponse(['status'=>false]);
    }

    public function ProductActivities($id)
    {
        $content = Content::first();
        $data = Activity::with('user', 
                                'product', 
                                'sales_d', 
                                'sales_h', 
                                'stock',
                                'category')
                                ->where('subject_id', '=', $id)
                                ->where('log_name', '=', 'Product')
                                ->orderBy('id', 'desc')
                                ->take($content->max_activities ?? 1000)
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
        $content = Content::first();
        $data = Product::where('product_active', 1)->orderBy('product_name', 'asc')->get();
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('master/product/'.$data->product_id.'/edit');
            $url = url('master/product/'.$data->product_id);
            
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
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
        ->editColumn('product_id', 'ID:{{$product_id}}')
        ->addColumn('checkbox', function($data){
            $chk = '<input type="checkbox" name="products_checkbox[]" class="products_checkbox" value="'. $data->product_id .'" />';
            return $chk;
        })
        ->rawColumns(['checkbox', 'action'])
        ->make(true);
    }

    public function dataTableTrash()
    {
        $content = Content::first();
        $data = Product::where('product_active', 0)->get();
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url = url('master/product/'.$data->product_id);
            $undoTrash = url('product/undoTrash/'.$data->product_id);
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
            
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
        ->editColumn('product_id', 'ID:{{$product_id}}')
        ->editColumn('stock_available', function($data){
            return number_format($data->stock_available, 0, '.', ',');
        })->addColumn('checkbox_t', function($data){
            $chk_t = '<input type="checkbox" name="products_trash_checkbox[]" class="products_trash_checkbox" value="'. $data->product_id .'" />';
            return $chk_t;
        })
        ->rawColumns(['checkbox_t', 'action'])
        ->make(true);
    }

    public function undoTrash($id){

        $data = Product::find($id);

        $data->product_active = 1;
        $data->user_modified = Auth::user()->id;
        
        if($data->save()){
            toastr()->success('Product activated successfully.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Product failed to deactivate.');
        return new JsonResponse(['status'=>false]);
        
    }

    public function datatable_product(){
        $data = Product::with(['product_category'])->where('product_active', 1)->get();
        
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

    public function productExport() 
    {
        return Excel::download(new ProductsExport, 'product-report.xlsx');
    }    

    public function deactivateProduct(Request $request)
    {
        $ids = $request->input('id');
        $sales = Product::whereIn('product_id', $ids)->update(array('product_active' => 0, 'user_modified' => Auth::user()->id));
  
        echo 'Product/s successfully deactivated.';
    }

    public function reactivateProduct(Request $request)
    {
        $ids = $request->input('id');
        $sales = Product::whereIn('product_id', $ids)->update(array('product_active' => 1, 'user_modified' => Auth::user()->id));
  
        echo 'Product/s successfully re-activated.';
    }
}
