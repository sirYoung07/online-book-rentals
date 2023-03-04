<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class VerificationController extends Controller
{
  public function verifyEmail(Request $request, User $user)
  {
    $validator = Validator::make($request->all(),[
      'token'=>['required']
    ]);

    if($validator->fails()){
      return $this->failure([
        'errors' => $validator->errors()
      ], '', self::VALIDATION_ERROR);
    }

    $token_exists = Code::where('token', $request->token);
 
    if($token_exists->get()->isEmpty()){
      return $this->failure(['error' => 'the submitted is invalid'], '', self::VALIDATION_ERROR);

    }
    
    $token_expiry_time = Carbon::parse(Code::where('codeable_id', Auth::id())
                              ->value('expires_at'));

    $token_created_time = Carbon::parse(Code::where('codeable_id', Auth::id())
                              ->value('created_at'));

    $time_difference = $token_expiry_time->diffInMinutes($token_created_time);
    
    
    if($time_difference > 10 ){
      $token_exists->delete();
      return $this->failure([
        'error' => 'the submitted token has expired'], 'please request a new verification code', self::VALIDATION_ERROR );
    }
    
    $user = User::find(Auth::user()->id);
    $user->email_verified_at = Carbon::now();
    $user->save();
    
    return $this->success([''], 'email verification successful'. self::SUCCESS);
    
    if(!$user->hasVerifiedEmail()){
      return $this->failure([], 'email verification failed');    
    }

  }
    
    
}
