<?php

use App\Models\passwordR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userC;
use App\Http\Controllers\PasswordRController;


//Public
Route::post('/register',[userC::class,'register']);
Route::post('/login',[userC::class,'signin']);

Route::post('/send-password=reset-email',[PasswordRController::class,'send_password_reset_email']);



//Protected
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[userC::class,'signout']);
    Route::get('/loggeduserdata',[userC::class,'logged_user']);
    Route::post('/changepassword',[userC::class,'change_password']);


});
