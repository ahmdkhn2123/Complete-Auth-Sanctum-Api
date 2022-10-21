<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
class userC extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
            'tc'=>'required',
        ]);

        if(User::where('email','=',$request->email)->first()){
            return response(
                [
                    'message'=>'Email Already Exist',
                    'status'=>'failed',
                ],200);

        }

        $user=User::create(
            [
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'tc'=>json_decode($request->tc),
            ]);

        $token=$user->createToken($request->email)->plainTextToken;

        return response([
            'token'=>$token,
            'message' =>'Registration Successful',
            'status'=>'success',
        ],201);
    }


    public function signin(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user=User::where('email',$request->email)->first();
        if($user && Hash::check($request->password,$user->password)){
            $token=$user->createToken($request->email)->plainTextToken;
            return response([
                'token'=>$token,
               'message' =>'Login Successful',
               'status'=>'success',
            ],200);
        }else{
            return response(
                [
                   'message'=>'Email or Password is incorrect',
                   'status'=>'failed',
                ],401);
        }
    }

    public function signout()
    {
        auth()->user()->tokens()->delete();
        return response(
            [
            'message'=>'Logout Successful',
            'status'=>'success'
            ],200);
    }

    public function logged_user()
    {
        $user=auth()->user();
        return response(
            [
                'details'=>$user,
                'message' =>'Logged User Data',
                'status'=>'success',
            ]);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' =>'required|confirmed',
        ]);

        $loggeduser=auth()->user();

        $loggeduser->password=Hash::make($request->password);

        $loggeduser->save();

        return response([
            'message'=>'Password Updated',
            'status'=>'success',
        ]);


    }
}
