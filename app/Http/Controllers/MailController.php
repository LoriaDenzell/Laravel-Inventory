<?php

namespace App\Http\Controllers;

use App\Mail\SignupEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function sendSignupEmail($name, $email, $verification_code, $password){

        $data = [
            'name' => $name,
            'verification_code' => $verification_code,
            'password' => $password
        ];

        Mail::to($email)->send(new SignupEmail($data));
    }
}
