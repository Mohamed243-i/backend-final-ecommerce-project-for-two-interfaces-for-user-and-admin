<?php

namespace App\Http\Controllers\api;
use App\Models\UserMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use Illuminate\Support\Facades\Auth;


class HasShownController extends Controller
{

        public function ShownByAdmin(){
            Message::where('status',0)->update(['status' => 1]);
            $messages= Message::all();
            return response()->json($messages);

        }

        public function ShownByUser(){
            $user_id=Auth::user()->id;
            UserMessage::where('user_id',$user_id)->update(['status' => 1]);
            $messages= UserMessage::all()->where('user_id',$user_id);
            return response()->json($messages);

        }
}
