<?php

namespace App\Http\Controllers\Master;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use App\Model\Master\Addon;
use Illuminate\Http\Request;
use App\Content;
use App\User;
use Auth;

class AddonController extends Controller
{
    public function index()
    {
        return view("addon.index");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addon_name' => 'required|max:190',
            'addon_cost' => 'required',
            'addon_active' => 'required',
        ]);

        if($validator->fails()){
            toastr()->warning('Failed to create new Product Addon. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = new Addon();

        $data->addon_name = $request->addon_name;
        $data->addon_cost = $request->addon_cost;
        $data->addon_active = $request->addon_active;
        $data->user_modified = $request->user()->id;

        if($data->save())
        {
            toastr()->success('Product Addon created successfully');
            return view('addon.index');
        }
        
        toastr()->error('Failed to create a new Product Addon');
        return Redirect::back()->withInput();
        
    }

    public function show($id)
    {
        $addon = Addon::with(['user_modify'])->where('id', $id)->first();

        if($addon->count() > 0){
            return view('addon.view', compact('addon'));
        }

        toastr()->error('Product Addon failed to retrieve data.');
        return view('addon.index');
    }
    
    public function edit($id)
    {
        $addon = Addon::with(['user_modify'])->where('id', $id)->first();

        if($addon->count() > 0){
            return view('addon.update', compact('addon'));
        }

        toastr()->error('Product Addon failed to retrieve data.');
        return view('addon.index');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'addon_name_edit' => 'required|max:190',
            'addon_cost_edit' => 'required',
            'addon_active_edit' => 'required',
        ]);

        if($validator->fails()){
            toastr()->warning('Failed to update Product Addon. Please check your inputs.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $addon = Addon::find($id);

        if($addon == null){
            toastr()->error('Product Addon failed to retrieve data.');
            return view('addon.index');
        }
       
        $addon->addon_name = $request->addon_name_edit;
        $addon->addon_cost = $request->addon_cost_edit;
        $addon->addon_active = $request->addon_active_edit;
        $addon->user_modified = $request->user()->id;

        if($addon->save()){
            toastr()->success('Product Addon successfully updated.');
            return view('addon.index');
        }

        toastr()->error('Product Addon failed to update.');
        return view('master/addon', $request);
    }

    public function destroy($id)
    {
        $addon = Addon::find($id);
        $addon->addon_active = 0;
        $addon->user_modified = Auth::user()->id;
       
        if($addon->save()){
            toastr()->success('Product Addon successfully deactivated.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Product Addon failed to deactivate.');
        return new JsonResponse(['status'=>false]);
    }

    public function datatable()
    { 
        $content = Content::first();
        $data = Addon::where('addon_active', '=', 1)
                        ->take($content->max_activities ?? 1000)
                        ->get();
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('master/addon/'.$data->id.'/edit');
            $url = url('master/addon/'.$data->id);

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning edit' href = '#' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            if (Auth::user()->hasRole('A')) {
                return $view."".$edit."".$delete;
            }else{ 
                return $view;
            }
        })
        ->editColumn('addon_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->addon_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('addon_cost', function($data){
            $string_replace_name = number_format($data->addon_cost, 0, '.', ',');
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('user_modified', function($data){

            $user = User::find($data->user_modified);
            $userFullName = $user->first_name. " ". $user->last_name;

            $string_replace_name = str_ireplace("\r\n", ',', $userFullName);
            return Str::limit($string_replace_name, 50, '...');
        })->addColumn('checkbox', function($data){
            $chk = '<input type="checkbox" name="addon_checkbox[]" class="addon_checkbox" value="'. $data->id .'" />';
            return $chk;
        })->rawColumns(['checkbox', 'action'])
        ->editColumn('id', '{{$id}}')
        ->make(true);
    }

    public function datatableTrash()
    {
        $content = Content::first();
        $data = Addon::where('addon_active', '=', 0)
                        ->take($content->max_activities ?? 1000)
                        ->get();
        
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url = url('master/addon/'.$data->id);
            $undoTrash = url('addon/undoTrash/'.$data->id);

            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Re-Activate'><i class='fas fa-trash-restore-alt'></i></button>";
            
            return $view."".$undo;

        })
        ->editColumn('addon_name', function($data){
            $string_replace_name = str_ireplace("\r\n", ',', $data->addon_name);
            return Str::limit($string_replace_name, 50, '...');
        })
        ->editColumn('addon_cost', function($data){
            $str = number_format($data->addon_cost, 0, '.', ',');
            return Str::limit($str, 25, '...');
        })
        ->editColumn('user_modified', function($data){

            $user = User::find($data->user_modified);
            $userFullName = $user->first_name. " ". $user->last_name;

            $string_replace_name = str_ireplace("\r\n", ',', $userFullName);
            return Str::limit($string_replace_name, 50, '...');
        })->addColumn('checkbox_t', function($data){
            $chk = '<input type="checkbox" name="addon_trash_checkbox[]" class="addon_trash_checkbox" value="'. $data->id .'" />';
            return $chk;
        })->rawColumns(['checkbox_t', 'action'])
        ->editColumn('id', '{{$id}}')
        ->make(true);
    }

    public function undoTrash(Request $request, $id)
    {
        $data = Addon::find($id);
        $data->addon_active = 1;
        $data->user_modified = Auth::user()->id;

        if($data->save()){
            toastr()->success('Product Addon successfully re-activated.');
            return new JsonResponse(['status'=>true]);
        }
        
        toastr()->error('Product Addon failed to re-activate.');
        return new JsonResponse(['status'=>false]);
        
    }

    public function datatable_addon()
    {
        $content = Content::first();

        $data = Addon::select('addon.*')
                ->where('addons.addon_active', '=', 1)
                ->take($content->max_activities ?? 1000)
                ->get();

        return Datatables::of($data)
                ->editColumn('addon_name', function($data){
                    $string_replace = str_ireplace("\r\n", ',', $data->addon_name);
                    return Str::limit($string_replace, 70, '...');
                })
                ->make(true);
    }

    public function AddonActivities($id)
    {
        $content = Content::first();

        $dataAct = Activity::with('user', 'addons')
                                ->where('subject_id', '=', $id)
                                ->where('log_name', '=', 'Addon')
                                ->orderBy('id', 'desc')
                                ->take($content->max_activities ?? 1000)
                                ->get();

        return Datatables::of($dataAct)
                            ->editColumn('created_at', function($data){
                                return date('d M Y - H:i:s', $data->created_at->timestamp);
                            })->make(true);
    }

    public function deactivateAddon(Request $request)
    {
        $ids = $request->input('id');
        $sales = Addon::whereIn('id', $ids)->update(array('addon_active' => 0, 'user_modified' => Auth::user()->id));
        echo 'Product Addon/s successfully deactivated.';
    }

    public function reactivateAddon(Request $request)
    {
        $ids = $request->input('id');
        $sales = Addon::whereIn('id', $ids)->update(array('addon_active' => 1, 'user_modified' => Auth::user()->id));
        echo 'Product Addon/s successfully re-activated.';
    }
}
