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
        try {
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
            return $this->success([
                'user' => $user,
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 'user logged in successfully', self::SUCCESS);

        }catch(\Throwable $e){
            return $this->failure([
                'error' => $e->getMessage()
            ], '', self::SERVER_ERROR);

        }
    
    }

    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();
            return $this->success([
                'message' => 'user successfully logged out'
            ],'', self::SUCCESS);

        }catch(\Throwable $e){
            return $this->failure([
                'error' => $e->getMessage()
            ], '', self::SERVER_ERROR);
        }
    }
} 
    