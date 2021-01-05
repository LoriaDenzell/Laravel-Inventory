<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\User;
use DB;

class UserController extends Controller
{
 
    public function index()
    {
        return view('user.index'); 
    }

    public function create()
    {
        return view("user.create");
    }

    public function edit($id)
    {
        $data = User::find($id);

        if($data != null){
            return view('user.update', compact('data'));
        }else{
            return redirect()->back();
        }
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users|max:150',
        ]);

        if($validator->fails()){
            
            Toastr::warning('E-Mail Address has already been used.', 'Warning');
            return redirect()->back()->withInput($request->all);
        }
        
        $pw = Str::random(10);

        $user = new User();
        $user->first_name = $request->u_fname_input;
        $user->last_name = $request->u_lname_input;
        $user->gender = $request->u_gender_input;
        $user->birthdate = $request->u_birthdate_input;
        $user->user_contact = $request->u_contact_input;
        $user->email = $request->u_email_input;
        $request->u_type_input == 'A' ? $user->assignRole('A') : $user->assignRole('E');
        
        $user->status = 0;
        $user->password = Hash::make($pw);
        $user->verification_code = sha1(time());
        $user->save();
        

        $userFullName = $request->u_fname_input." ".$request->u_lname_input;

        if($user != null){
            
            MailController::sendSignupEmail($userFullName, $user->email, $user->verification_code, $pw);

            $currentRole = null;

            if($user->hasRole('A')){
                $currentRole = "Administrator";
            }else if($user->hasRole('E')){
                $currentRole = "Employee";
            }

            Toastr::success('Successfully created an '. $currentRole. ' account. E-Mail address for account verification has been also sent.', 'Success');
            return view('user.index');
        }else{
            Toastr::error('Failed to create an'. $currentRole. ' account.', 'Error');
            return redirect()->back();
        }

    }

    public function show($id)
    {
        $data = User::where('id', $id)->first();
        
        if($data != null){
            return view('user.view', compact('data'));
        }else{
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id);
       
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->gender = $request->gender;
        $data->user_contact = $request->user_contact;
        $data->birthdate = $request->birthdate;
        $data->email = $request->email;

        if($data->save()){
            Toastr::success('You successfully updated your profile information.', 'Success');
            return view('user.view', compact('data'));
        }else{
            Toastr::error('Failed to update your profile information.', 'Error');
            return redirect()->back();
        }
    }

    public function datatable()
    {
        $data = User::where('status', 1);
     
        return DataTables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('user/'.$data->id.'/edit');
            $url = url('user/'.$data->id);
            
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            return $view."".$edit."".$delete; 
        }) 
        ->rawColumns(['action'])
        ->editColumn('id', 'ID:{{$data->id}}')
        ->make(true);
    }

    public function datatableTrash()
    {
        $data = User::where('status', '!=', 1)
                -orderBy('id', 'asc')
                ->get();
        
        return Datatables::of($data)
            ->addColumn('action', function($data){

            $url_edit = url('user/'.$data->id.'/edit');
            $url = url('user/'.$data->id);
            $undoTrash = url('user/undoTrash/'.$data->id);
            
            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
            $delete = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-danger' title = 'Undo Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
            
            return $view."".$edit."".$delete;
        }) 
        ->editColumn('user_type', function($data){

            $dataUser = User::find($data->id)->first();
            $returnStr = "";

            if($dataUser->hasRole('A')){
                $returnStr = "Administrator";
            }else{
                $returnStr = "Employee";
            }

            return $returnStr;
        })
        ->rawColumns(['action'])
        ->editColumn('id', 'ID:{{$user_id}}')
        ->make(true);
    }

    public function undoTrash($id){

        $data = User::find($id);

        $data->status = 1;
        $data->user_modified = Auth::user()->id;
        
        if($data->save()){
            Toastr::success('User has been activated successfully', 'Success');
            return new JsonResponse(['status'=>true]);
        }else{
            Toastr::error('User cannot be activated.', 'Error');
            return new JsonResponse(['status'=>false]);
        }
    }

    public function Password()
    {
        return view('user.change_password');
    }
    
    public function Calendar()
    {
        return view('user.calendar');
    }


    public function updatePassword(Request $request)
    {
        // Input password first before proceeding
        if (!(Hash::check($request->get('current_pw'), Auth::user()->password))) {
            Toastr::error('Your current password does not matched with the password you provided. Please try again.', 'Error');
            return redirect()->back();
        }

        //Current password and new password are same
        if(strcmp($request->get('current_pw'), $request->get('new_pw')) == 0){
            Toastr::error('New Password cannot be same as your current password. Please choose a different password.', 'Error');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'current_pw' => 'required',
            'new_pw' => 'required',
            'confirm_pw' => 'required|min:8|same:new_pw'
        ]);

        if ($validator->fails()) {
            Toastr::error('Password did not followed the guidelines. Please input a new password that follows guidelines stated above.', 'Error');
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->get('new_pw'));
        
        if($user->save()){

            activity()
            ->useLog("User")
            ->causedBy(Auth::user())
            ->performedOn(Auth::user())
            ->log('Updated the password.');

            Toastr::success('Account password successfully changed.', 'Success');
            return view('user.index');
        }else{
            Toastr::error('Account password failed to change.', 'Error');
            return redirect()->back();
        }
    }

    public function userActivities($id)
    {
        $data = Activity::all()->where('causer_id', '=', $id)->take(1000);
                            
        return Datatables::of($data)
                ->editColumn('created_at', function($data){
                     return date('d M Y - H:i:s', $data->created_at->timestamp);
                })->make(true);
    }
}
