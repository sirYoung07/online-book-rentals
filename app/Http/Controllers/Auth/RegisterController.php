<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\EmailVerificatonController;
use Illuminate\Validation\Rules\Password;
use PHPUnit\Framework\TestFailure;

class RegisterController extends Controller
{
    // user
    public function registeruser(Request $request){
    
            $formFields = $request->validate([
                'first_name' => ['required', 'string','min:3','max:64'],
                'last_name' => ['required', 'string', 'min:3', 'max:64'],
                'email' => ['required', Rule::unique('users', 'email')],
                'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
            ]);

            $formFields['password'] = bcrypt($formFields['password']);
            $user = User::create($formFields);
            

            if (!$user) {
                return $this->failure([], 'Registration fail', self::SERVER_ERROR);
            } 

            
            return $this->success([
                'user'=> $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
                'token type' => 'Bearer',
            ], ' User Registration successful.', self::CREATED);
        
       
    }

    // admin
    public function registeradmin(Request $request){
        $formFields = $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
            //'role' => ['required', 'string', 'exists:roles,name']
        ]);

        $password = $formFields['password'] = bcrypt($formFields['password']);
        $admin = new User([
            'first_name' => $formFields['first_name'],
            'last_name' => $formFields['last_name'],
            'email' => $formFields['email'],
            'passwprd' => $password,
            'roles' => 1
        ]);
        $admin->save();
        

        if (!$admin) {
            return $this->failure([], 'Registration fail', self::SERVER_ERROR);
        } 

        
        return $this->success([
            'user'=> $admin,
            'token' => $admin->createToken('auth_token')->plainTextToken,
            'token type' => 'Bearer',
        ], 'Admin Registration successful.', self::CREATED);
    }

    //superadmin
    public function registersuperadmin(Request $request){
        $formFields = $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
            //'role' => ['required', 'string', 'exists:roles,name']
        ]);

        $password = $formFields['password'] = bcrypt($formFields['password']);
        $superadmin = new User([
            'first_name' => $formFields['first_name'],
            'last_name' => $formFields['last_name'],
            'email' => $formFields['email'],
            'passwprd' => $password,
            'roles' => 2
        ]);
        $superadmin->save();
        

        if (!$superadmin) {
            return $this->failure([], 'Registration fail', self::SERVER_ERROR);
        } 

        
        return $this->success([
            'user'=> $superadmin,
            'token' => $superadmin->createToken('auth_token')->plainTextToken,
            'token type' => 'Bearer',
        ], 'SuperAdmin Registration successful.', self::CREATED);
    }

}

