<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Activitylog\Models\Activity;
use TJGazel\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $request->flash();

        $inputs = $request;

        $remember = $request->has('remember') ? true : false;
        
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'status' => 1 ], $remember))
        {
            activity()
            ->useLog("User")
            ->causedBy(Auth::user())
            ->performedOn(Auth::user())
            ->log('Logged-In.');
            
            return redirect('/home');
        }else{
            Toastr::error('The E-Mail Address and/or Password you inputted is wrong.', 'Error');
            return redirect('/login')->with("old_email", $request->email);
        }
    }
}
