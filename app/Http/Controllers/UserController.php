<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MailController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
use App\Content;
use DB;

class UserController extends Controller
{
 
    public function index()
    {
        $activeUsers = User::where('status', 1)->get();
        $inactiveUsers = User::where('status', 0)->get();
        return view('user.index', compact('activeUsers', 'inactiveUsers')); 
    }

    public function edit($id)
    {
        $data = User::find($id);

        if($data == null)
        {
            toastr()->error('User failed to retrieve data.');
            return redirect()->back();
        }

        return view('user.update', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users|required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'user_contact' => 'required',
            'u_type_input' => 'required',
        ]);

        if($validator->fails())
        {
            toastr()->warning('Failed to create new User. Please check your inputs.');
            return redirect()->back()->withInput($request->all);
        }
        
        $pw = Str::random(10);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->user_contact = $request->user_contact;
        $user->email = $request->email;
        $request->u_type_input == 'A' ? $user->assignRole('A') : $user->assignRole('E');
        
        $user->status = 0;
        $user->password = Hash::make($pw);
        $user->verification_code = sha1(time());
        $user->save();
        
        $userFullName = $request->u_fname_input." ".$request->u_lname_input;

        if($user == null)
        {
            toastr()->error('Failed to create an'. $currentRole. ' account.');
            return redirect()->back();
        }

        $currentRole = $user->hasRole('A') ? "Administrator" : "Employee";
        MailController::sendSignupEmail($userFullName, $user->email, $user->verification_code, $pw);

        toastr()->success('Successfully created an '. $currentRole. ' account. E-Mail address for account verification has been also sent.');
        
        $activeUsers = User::where('status', 1)->get();
        $inactiveUsers = User::where('status', 0)->get();

        return view('user.index', compact('activeUsers', 'inactiveUsers')); 
    }

    public function show($id)
    {
        $data = User::where('id', $id)->first();
        
        if($data == null)
        {
            toastr()->error('User failed to retrieve data.');
            return redirect()->back();
        }
        
        return view('user.view', compact('data'));
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

        if($data->save())
        {
            toastr()->success('User Successfully updated.');
            return view('user.view', compact('data'));
        }

        toastr()->error('User failed to update.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $data = User::find($id);
        $data->status = 0;
        $fullName = $data->first_name." ".$data->last_name;
        $data->user_modified = Auth::user()->id; 

        if($data->save())
        {
            toastr()->success('User Account deactivated successfully ['. $fullName. ']');
            return new JsonResponse(['status'=>true]);
        }

        toastr()->error('Failed to Deactivate the User Account ['. $fullName. ']');
        return new JsonResponse(['status'=>false, 'url'=> route('user.index')]);
    }

    public function undoTrash($id)
    {
        $data = User::find($id);

        $data->status = 1;
        $data->user_modified = Auth::user()->id;
        
        if($data->save()){
            toastr()->success('User successfully re-activated.');
            return new JsonResponse(['status'=>true]);
        }

        toastr()->error('User failed to re-activate.');
        return new JsonResponse(['status'=>false]);
    }

    public function Password()
    {
        return view('user.change_password');
    }
    
    public function updatePassword(Request $request)
    {
        // Input password first before proceeding
        if (!(Hash::check($request->get('current_pw'), Auth::user()->password))) {
            toastr()->warning('Your current password does not matched with the password you provided. Please try again.');
            return redirect()->back();
        }

        //Current password and new password are same
        if(strcmp($request->get('current_pw'), $request->get('new_pw')) == 0){
            toastr()->warning('New Password cannot be same as your current password. Please choose a different password.');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'current_pw' => 'required',
            'new_pw' => 'required',
            'confirm_pw' => 'required|min:8|same:new_pw'
        ]);

        if ($validator->fails()) {
            toastr()->warning('Password did not followed the guidelines. Please input a new password that follows guidelines stated above.');
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

            toastr()->success('Account password successfully changed.');
            return view('user.index');
        }
        
        toastr()->error('Account password failed to change.');
        return redirect()->back();
    }

    public function userActivities($id)
    {
        $content = Content::first();
        $data = Activity::where('causer_id', $id)->take($content->max_activities ?? 1000)->orderBy('id', 'DESC')->get();
                            
        return Datatables::of($data)
                ->editColumn('created_at', function($data){
                     return date('d M Y - H:i:s', $data->created_at->timestamp);
                })->make(true);
    }

    public function deactivateUser(Request $request)
    {
        $ids = $request->input('id');
        $users = User::whereIn('id', $ids)->update(array('status' => 0, 'user_modified' => Auth::user()->id));
  
        echo 'User/s successfully deactivated.';
    }

    public function reactivateUser(Request $request)
    {
        $ids = $request->input('id');
        $users = User::whereIn('id', $ids)->update(array('status' => 1, 'user_modified' => Auth::user()->id));
  
        echo 'User/s successfully reaactivated.';
    }
}
