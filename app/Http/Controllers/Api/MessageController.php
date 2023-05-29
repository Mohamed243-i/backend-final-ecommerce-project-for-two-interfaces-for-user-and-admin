<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\user\MessageRequest;

class MessageController extends Controller
{

    public function showMessages()
    {
        try{
            $messages=Message::all();
        return response()->json($messages);
        }
        catch (\Throwable $th) {
            return response()->json('somthing is wrong');
        }
    }


    public function sendMessage(MessageRequest $request)
    {

        $user_id=Auth::user()->id;
        $message=new Message();
        $message->userName=$request->userName;
        $message->useremail=$request->userEmail;
        $message->userMessage=$request->userMessage;
        $message->user_id=$user_id;
        $message->save();
        return $message;

    }

}
