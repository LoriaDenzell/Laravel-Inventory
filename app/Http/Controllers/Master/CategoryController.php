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
use App\Content;
use App\User;
use Auth;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        return view("category.index");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|unique:categories|max:190',
            'category_status' => 'required',
        ]);

        if($validator->fails())
        {
            toastr()->warning('Failed to create new Product Category. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new Category();

        $data->category_name = $request->category_name;
        $data->category_status = $request->category_status;
        $data->user_modified = $request->user()->id;

        if($data->save())
        {
            toastr()->success('Product Category created successfully.');
            return view('category.index');
        }
        
        toastr()->error('Failed to create new Product Category');
        return Redirect::back()->withInput();
    }

    public function show($id)
    {
        $category = Category::find($id);

        $data = User::select('first_name', 'last_name')
                    ->where('id', '=', $category->user_modified)->first();
                   
        if($data->count() > 0)
        {
            return view('category.view', compact('category','data'));
        }

        toastr()->error('Product Category failed to retrieve data.');
        return view('category.index');
    }

    public function edit($id)
    {
        $data = Category::where('category_id', $id)->first();

        if($data->count() > 0)
        {
            return view('category.update', compact('data'));
        }

        toastr()->error('Product Category failed to retrieve data.');
        return view('category.index');
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_status' => 'required',
        ]);

        if($validator->fails()){
            toastr()->warning('Failed to update Product Addon. Please check your inputs.');
            return Redirect::back()->withErrors($validator);
        }

        $data = Category::find($id);

        if($data == null){
            toastr()->error('Product Category failed to retrieve data.');
            return view('category.index');
        }
        
        $data->category_name = $request->category_name;
        $data->category_status = $request->category_status;
        $data->user_modified = $request->user()->id;

        if($data->save()){
            toastr()->success('Product Category updated successfully.');
            return view('category.index');
        }

        toastr()->error('Product Category failed to update.');
        return view('category.index');
    }

    public function destroy($id)
    {
        $data = Category::find($id);
        $data->category_status = 0;
        $data->user_modified = Auth::user()->id;
       
        if($data->save()){
            toastr()->success('Product Category deactivated successfully.');
            return new JsonResponse(['status'=>true]);
        }

        toastr()->error('Product Category failed to deactivate.');
        return new JsonResponse(['status'=>false]);
    }

    public function datatable()
    { 
        $content = Content::first();
        $data = Category::where('category_status', '=', 1)
                        ->take($content->max_activities ?? 1000)
                        ->get();
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('master/category/'.$data->category_id.'/edit');
            $url = url('master/category/'.$data->category_id);

            $view = "<a class = 'btn btn-primary view' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning edit' href = '#' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            return $view."".$edit."".$delete;

        })
        ->editColumn('category_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->category_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('user_modified', function($data){

            $user = User::find($data->user_modified);
            $userFullName = $user->first_name. " ". $user->last_name;

            $string_replace_name = str_ireplace("\r\n", ',', $userFullName);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('category_id', '{{$category_id}}')
        ->addColumn('checkbox', function($data){
            $chk = '<input type="checkbox" name="category_checkbox[]" class="category_checkbox" value="'. $data->category_id .'" />';
            return $chk;
        })->rawColumns(['checkbox', 'action'])
        ->make(true);
    }

    public function datatableTrash()
    {
        $content = Content::first();
        $data = Category::where('category_status', '=', 0)
                        ->take($content->max_activities ?? 1000)
                        ->get();
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url = url('master/category/'.$data->category_id);
            $undoTrash = url('category/undoTrash/'.$data->category_id);

            $view = "<a class = 'btn btn-primary view' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
            
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
        ->editColumn('category_id', '{{$category_id}}')
        ->addColumn('checkbox_t', function($data){
            $chk = '<input type="checkbox" name="category_trash_checkbox[]" class="category_trash_checkbox" value="'. $data->category_id .'" />';
            return $chk;
        })->rawColumns(['checkbox_t', 'action'])
        ->make(true);
    }

    public function undoTrash(Request $request, $id)
    {
        $data = Category::find($id);
        $data->category_status = 1;
        $data->user_modified = Auth::user()->id;

        if($data->save())
        {
            toastr()->success('Product Category activated successfully.');
            return new JsonResponse(['status'=>true]);
        }
        else
        {
            toastr()->error('Product Category failed to deactivate.');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function datatable_category()
    {
        $content = Content::first();
        $data = Category::select('category.*')
                        ->where('categories.category_status', '=', 1)
                        ->orderBy('id', 'desc')
                        ->take($content->max_activities ?? 1000)
                        ->get();

        return Datatables::of($data)
                ->editColumn('category_name', function($data){
                    $string_replace = str_ireplace("\r\n", ',', $data->category_name);
                    return Str::limit($string_replace, 70, '...');
                })->addColumn('checkbox_t', function($data){
                    $chk = '<input type="checkbox" name="category_trash_checkbox[]" class="category_trash_checkbox" value="'. $data->category_id .'" />';
                    return $chk;
                })->rawColumns(['checkbox_t', 'action'])
                ->make(true);
    }

    public function CategoryActivities($id)
    {
        $content = Content::first();

        $dataAct = Activity::with('user', 
                                'category')
                                ->where('subject_id', '=', $id)
                                ->where('log_name', '=', 'Category')
                                ->orderBy('created_at', 'desc')
                                ->take($content->max_activities ?? 1000)
                                ->get();

        return Datatables::of($dataAct)
                    ->editColumn('created_at', function($data){
                        return date('d M Y - H:i:s', $data->created_at->timestamp);
                    })->make(true);
    }

    public function deactivateCategory(Request $request)
    {
        $ids = $request->input('id');
        $sales = Category::whereIn('category_id', $ids)->update(array('category_status' => 0, 'user_modified' => Auth::user()->id));
        echo 'Product Categories successfully deactivated.';
    }

    public function reactivateCategory(Request $request)
    {
        $ids = $request->input('id');
        $sales = Category::whereIn('category_id', $ids)->update(array('category_status' => 1, 'user_modified' => Auth::user()->id));
        echo 'Product Categories successfully re-activated.';
    }
}
