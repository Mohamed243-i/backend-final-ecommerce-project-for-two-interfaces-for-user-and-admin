<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Requests\user\ResetPasswordRequest;
use App\Http\Requests\user\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Notifications\ForgetPasswordNotification;
use App\Models\User;



class ForgetPasswordController extends Controller
{
   public function forgetPassword(ForgetPasswordRequest $request){
    $input=$request->only('email');

   // dd($input);
    $user=User::where('email',$input)->first();
    $user->notify(new ResetPasswordVerificationNotification());
    $success['success']=true;
    return response()->json($success,200);
   }
}
