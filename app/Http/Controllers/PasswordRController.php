<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\passwordR;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\str;
use Carbon\Carbon;

class PasswordRController extends Controller
{
    public function send_password_reset_email(Request $request)
    {
        $request->validate([
            'email' =>'required|email',
        ]);

        $user=User::where('email','=',$request->email)->first();

        if(!$user){
            return response([
                'message' =>'User Not Exist',
                'status' => 'try again',
            ]);
        }

        $token=str::random(60);

        //saving Data to Password Reset Table

        passwordR::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),

        ]);

        dump("http://127.0.0.1:3000/api/user/reset" . $token);

        return response([
            'message' =>'Password Reset Email Has Been Sent, Kindly check',
            'status' => 'Success',
        ]);
    }

}
