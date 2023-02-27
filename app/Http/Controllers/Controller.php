<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const SUCCESS = 200;
    protected const CREATED = 201;
    protected const UPDATED = 202;
    protected const DELETED = 204;

    protected const BAD_REQUEST = 400;
    protected const UNAUTHORIZED = 401;
    protected const PAYMENT_REQUIRED = 402;
    protected const ACCESS_DENIED = 403;
    protected const NOT_FOUND = 404;
    protected const BAD_METHOD = 405;
    protected const VALIDATION_ERROR = 422;
    protected const TOO_MANY_REQUESTS = 429;

    protected const SERVER_ERROR = 500;

    public function success(array $data = [], string  $message = '', int $statuscode = self::SUCCESS){
        return response()->json([
            'status' => true,
            'message' => $message,
            $data
        ], $statuscode);
    }

    public function failure(array $data = [], string $message = '', int $statuscode = self::BAD_REQUEST){
        return response()->json([
            'status' => false,
            'message' => $message,
            $data
        ], $statuscode);
    }

    public function generatecode(User $user){
        $code = mt_rand(000000, 999999);
        $save = $user->codes()->create([
            'token' => $code,
            'expires_at' => now()->addMinutes(20)
        ]);
        return $code ;
    }

    public function sendMailVerificationCode(User $user){ 
        $token = $this->generatecode($user);
        $user->notify(new EmailVerificationNotification($token));
    }

}
