<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    //
    public function register(Request $request){
        $formFields = $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->uncompromised()->numbers()->symbols()->mixedCase()->letters()],
            //'role' => ['required', 'string', 'exists:roles,name']
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $user = User::create($formFields);
        return $this->success([
            'user'=> $user,
            'token' => $user->createToken('access')->plainTextToken
        ], 'Registration successful', self::CREATED);
    }
}
