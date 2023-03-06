<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Routing\RequestContextAwareInterface;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $login = Auth::guard()->attempt($formFields);
        if(!$login){
            return $this->failure([
                'error' => 'invalid email or password'
            ], 'login unsuccessful', self::UNAUTHORIZED);
        }

        $user = auth()->user();
        $this->resendcode($user);
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'message' => 'use the six digit code sent to your mail to verify your email address'
        ], 'user logged in successfully', self::SUCCESS);

    }

    public function logout(Request $request){
    
        $request->user()->tokens()->delete();
        return $this->success([
            'message' => 'user successfully logged out'
        ],'', self::SUCCESS);

    }
} 
    