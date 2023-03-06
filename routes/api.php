<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', fn()=>['status'=> true, 'message'=> 'Api is running']); 

Route::group(['prefix' => 'auth'], function () {
   Route::post('login', [AuthController::class, 'login']);
   Route::post('register',[RegisterController::class, 'register']);
   
   
   Route::group(['middleware' => 'auth:sanctum'], function() {
         Route::post('logout', [AuthController::class, 'logout']);
         Route::post('verification/send',[VerificationController::class, 'sendMailVerificationCode']);
         Route::post('verification/verify', [VerificationController::class, 'verifyEmail']);
         Route::post('verification/resend', [Controller::class, 'resendcode']);
     });
    
     Route::group(['prefix'=> 'password', 'middleware' => 'guest:sanctum'], function() {
        Route::post('sendtoken', [PasswordController::class, 'sendcode']);
        Route::post('resendtoken', [PasswordController::class, 'sendcode']);
        Route::post('reset', [PasswordController::class, 'reset']);

        
    });
});


