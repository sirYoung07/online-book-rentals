<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\returnSelf;

use App\Notifications\AdminRoleNotification;
use App\Notifications\SuperAdminRoleNotification;

class UserConroller extends Controller
{
    //
    public function changerole(Request $request, User $user)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'roles' => ['required', 'int']
        ]);

        $auth_email = Auth::user()->email;
        $email = $user->where('email', $request->input('email'))->value('email');

        if($auth_email != $email){
            return $this->failure(['message' => 'this is not your email, please provide your email']);
        }
        
        $role = $request->input('roles');
        $to_new_role = $user->where('email', $request->input('email'))->first();
        
        switch($role){
            case 1;
                    $to_new_role->update(['roles' => $request->input('roles')]);
                    $to_new_role->notify(new AdminRoleNotification);
                    return $this->success(['message' => 'you are now an admin']);
                    break;

            case 2;
                    $to_new_role->update(['roles' => $request->input('roles')]);
                    $to_new_role->notify(new SuperAdminRoleNotification);
                    return $this->success(['message' => 'you are now an superadmin']);
                    break;

            default :

                    return $this->failure(['message' => 'there is no role with' . ' '.  $role]);
        }

    }
}
