<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserMessage;

class MessageResponse extends Controller
{
    public function sendResponse(Request $request)
    {
        // $user_id=1;
        $message=new UserMessage();
        $message->user_id=$request->user_id;
        $message->useremail=$request->userEmail;
        $message->userMessage=$request->userMessage;
        $message->save();
        return $message;

    }
    public function showResponse(){
        $user_id=Auth::user()->id;
        try{
            // $messages= UserMessage::find($user_id);
            $messages = UserMessage::where('user_id', $user_id)->get();
            return response()->json($messages);
        }
        catch (\Throwable $th) {
            return response()->json('somthing is wrong');
        }
    }
}
