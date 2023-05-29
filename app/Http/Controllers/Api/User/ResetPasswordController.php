<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\user\ResetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Notifications\ForgetPasswordNotification;
use App\Models\User;
use Otp;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp=new Otp();
    }
    public function resetPassword(ResetPasswordRequest $request){

     $otp2=   $this->otp->validate($request->email,$request->otp);
     if( !$otp2->status){
        return response()->json(['error'=>$otp2],401);
     }
        $input=$request->only('email');
        $user=User::where('email',$input)->first();
        $user->update(['password'=>Hash::make($request->password)]);
        // $user->tokens()->delete();
        $success['success']=true;
        return response()->json($success,200);
       }
}
