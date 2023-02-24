<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;

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

Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);




