<?php

use App\Http\Controllers\Admin\AdminConroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\User\UserConroller;

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

//user routes
Route::group(['prefix' => 'user'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'loginuser']);
        Route::post('register',[RegisterController::class, 'registeruser']);
        
        
        Route::group(['middleware' => 'auth:user'], function() {
     
              Route::post('logout', [AuthController::class, 'logoutuser']);
              Route::post('verification/send',[VerificationController::class, 'sendMailVerificationCode']);
              Route::post('verification/verify', [VerificationController::class, 'verifyEmail']);
              Route::post('verification/resend', [Controller::class, 'resendcode']);
     
        });

    });
});





// admin
Route::group(['prefix' => 'admin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'loginadmin']);
        Route::post('register',[RegisterController::class, 'registeradmin']);
        
        
        Route::group(['middleware' => 'auth:admin'], function() {
     
              Route::post('logout', [AuthController::class, 'logoutadmin']);
              Route::post('verification/send',[VerificationController::class, 'sendMailVerificationCode']);
              Route::post('verification/verify', [VerificationController::class, 'verifyEmail']);
              Route::post('verification/resend', [Controller::class, 'resendcode']);
     
        });

    });
});






//superadmin
Route::group(['prefix' => 'superadmin'], function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'loginsuperadmin']);
        Route::post('register',[RegisterController::class, 'registersuperadmin']);
        
        
        Route::group(['middleware' => 'auth:superadmin'], function() {
     
              Route::post('logout', [AuthController::class, 'logoutsuperadmin']);
              Route::post('verification/send',[VerificationController::class, 'sendMailVerificationCode']);
              Route::post('verification/verify', [VerificationController::class, 'verifyEmail']);
              Route::post('verification/resend', [Controller::class, 'resendcode']);
     
        });

    });
});



// reset password routes
    
Route::group(['prefix'=> 'password', 'middleware' => 'guest:sanctum'], function() {

    Route::post('sendtoken', [PasswordController::class, 'sendcode']);
    Route::post('resendtoken', [PasswordController::class, 'sendcode']);
    Route::post('reset', [PasswordController::class, 'reset']);

    
});







