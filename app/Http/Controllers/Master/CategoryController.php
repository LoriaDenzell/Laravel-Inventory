<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Model\Master\Category;
use Illuminate\Support\Str;
use App\User;
use Auth;
use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("category.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("category.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|unique:categories|max:150',
            'category_status' => 'required',
        ]);

        if($validator->fails()){
            Toastr::warning('Category name cannot be repeated or blank.', 'Warning');
            return Redirect::back()->withErrors($validator)->withInput();
        }else{

            $data = new Category();

            $data->category_name = $request->category_name;
            $data->category_status = $request->category_status;
            $data->user_modified = $request->user()->id;

            if($data->save()){
                Toastr::success('Product Category created Successfully', 'Success');
                return view('category.index');
            }else{
                Toastr::error('Failed to create new Product Category', 'Error');
                return Redirect::back()->withInput();
            }
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
        $category = Category::find($id);

        $data = User::select('first_name', 'last_name')
                    ->where('id', '=', $category->user_modified)->first();
                   
        if($data->count() > 0){
            return view('category.view', compact('category','data'));
        }else{
            Toastr::error('Product Category cannot be retrieved.', 'Error');
            return view('category.index');
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
        $data = Category::where('category_id', $id)->first();

        if($data->count() > 0){
            return view('category.update', compact('data'));
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
        $data = Category::where('category_id', '=', $id)->first();
       
        $data->category_name = $request->category_name;
        $data->category_status = $request->category_status;
        $data->user_modified = $request->user()->id;

        if($data->save()){
            Toastr::success('Product Category updated successfully', 'Success');
            return view('category.index');
        }else{
            Toastr::error('Product Category cannot be updated.', 'Error');
            return view('category.index', $request);
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
        $data = Category::find($id);
        $data->category_status = 2;
        $data->user_modified = Auth::user()->id;
       
        if($data->save()){
            Toastr::success('Product Category Deleted successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Product Category cannot be Deleted.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function datatable()
    { 
        $data = Category::where('category_status', '!=', 2);
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('master/category/'.$data->category_id.'/edit');
            $url = url('master/category/'.$data->category_id);

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            return $view."".$edit."".$delete;

        })
        ->editColumn('category_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->category_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('user_modified', function($data){

            $addedBy_ID = User::find($data->user_modified);
            $addedByFullName = $addedBy_ID->first_name. " ". $addedBy_ID->last_name;

            $string_replace_name = str_ireplace("\r\n", ',', $addedByFullName);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->rawColumns(['action'])
        ->editColumn('category_id', 'ID:{{$category_id}}')
        ->make(true);
    }

    public function datatableTrash()
    {
        $data = Category::where('category_status', '=', 2);
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url = url('master/category/'.$data->category_id);
            $undoTrash = url('category/undoTrash/'.$data->category_id);

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-danger' title = 'Undo Delete'>Activate Product</button>";
            
            return $view."".$undo;

        })
        ->editColumn('category_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->category_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('user_modified', function($data){

            $addedBy_ID = User::find($data->user_modified);
            $addedByFullName = $addedBy_ID->first_name. " ". $addedBy_ID->last_name;

            $string_replace_name = str_ireplace("\r\n", ',', $addedByFullName);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->rawColumns(['action'])
        ->editColumn('category_id', 'ID:{{$category_id}}')
        ->make(true);
    }

    public function undoTrash(Request $request, $id){
        $data = Category::find($id);
        $data->category_status = 1;
        $data->user_modified = Auth::user()->id;

        if($data->save()){
            Toastr::success('Product Category has been activated successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('Product Category cannot be activated.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function datatable_category(){
        $data = Category::select('category.*')->where('categories.category_status', '!=', '0');

        return Datatables::of($data)
                ->editColumn('category_name', function($data){
                    $string_replace = str_ireplace("\r\n", ',', $data->category_name);
                    return Str::limit($string_replace, 70, '...');
                })
                ->make(true);
    }

    public function CategoryActivities($id)
    {
        $dataAct = Activity::with('user', 
                                'category')
                                ->where('subject_id', '=', $id)
                                ->where('log_name', '=', 'Category')
                                ->orderBy('created_at', 'desc')
                                ->get();

        return Datatables::of($dataAct)
                            ->editColumn('created_at', function($data){
                                return date('d M Y - H:i:s', $data->created_at->timestamp);
                            })->make(true);
    }

}
