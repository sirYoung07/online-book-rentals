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
    // user/reader
    public function loginuser(Request $request){
        
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
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 'user logged in successfully', self::SUCCESS);

    }

    public function logoutuser(Request $request){
    
        $request->user()->tokens()->delete();
        return $this->success([
            'message' => 'user successfully logged out'
        ],'', self::SUCCESS);

    }






    // admin/renters
    public function loginadmin(Request $request){
        
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        

        $login = Auth::guard('admin')->attempt($formFields);
        if(!$login){
            return $this->failure([
                'error' => 'invalid email or password'
            ], 'login unsuccessful', self::UNAUTHORIZED);
        }
        

        $admin = Auth::guard('admin')->user();
        return $this->success([
            'user' => $admin,
            'token' => $admin->createToken('API TOKEN')->plainTextToken,
        ], 'user logged in successfully', self::SUCCESS);

    }

    public function logoutadmin(){
        Auth::guard('admin')->logout();

        return $this->success([
            'message' => 'Successfully logged out'
        ],'', self::SUCCESS);

    }



    



    // superadmin
    public function loginsuperadmin(Request $request){
        
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $login = Auth::guard('superadmin')->attempt($formFields);

        if(!$login){
            return $this->failure([
                'error' => 'invalid email or password'
            ], 'login unsuccessful', self::UNAUTHORIZED);
        }

        $user = Auth::guard('superadmin')->user();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'message' => 'use the six digit code sent to your mail to verify your email address'
        ], 'user logged in successfully', self::SUCCESS);

    }

    public function logoutsuperadmin(){
        Auth::guard('superadmin')->logout();
        return $this->success([
            'message' => 'Successfully logged out'
        ],'', self::SUCCESS);

    }

}


    