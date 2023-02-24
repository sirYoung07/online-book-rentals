<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    //
    
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

