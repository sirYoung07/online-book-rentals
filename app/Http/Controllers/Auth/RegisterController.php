<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\EmailVerificatonController;
use Illuminate\Validation\Rules\Password;
use PHPUnit\Framework\TestFailure;

class RegisterController extends Controller
{
    //
    public $send;
    
    public function register(Request $request){
        try {
            $formFields = $request->validate([
                'first_name' => ['required', 'string','min:3','max:64'],
                'last_name' => ['required', 'string', 'min:3', 'max:64'],
                'email' => ['required', Rule::unique('users', 'email')],
                'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
                //'role' => ['required', 'string', 'exists:roles,name']
            ]);

            $formFields['password'] = bcrypt($formFields['password']);
            $user = User::create($formFields);
            

            if (!$user) {
                return $this->failure([], 'Registration fail', self::SERVER_ERROR);
            } 
            $this->sendMailVerificationCode($user);
            

            return $this->success([
                'user'=> $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
                'token type' => 'Bearer'
            ], 'Registration successful', self::CREATED);
        
        } catch(\Throwable $e){
            return $this->failure([
                'error' => $e->getMessage()
            ], '', self::SERVER_ERROR);
        }
    }
}

