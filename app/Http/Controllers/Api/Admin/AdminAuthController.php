<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Auth\TokenGuard;
use App\Models\Admin;
use App\Http\Resources\UserResourse;
use App\Models\User;
use App\Http\Requests\Admin\UpdateRequest;



class AdminAuthController extends Controller
{
    public function adminLogin(Request $request){


        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        try {
            // if(Auth::guard('admin-api')->attempt($credentials)){
              $admin = Admin::where('email', $request->email)->first();
              if(!$admin){
                return 'email or password is wrong';
              }
              // $admin=Auth::guard('admin-api')->user();
             $adminToken= $admin->createToken('token-name', ['server:update'])->plainTextToken;
              // $token = $user->createToken($request->token_name);
              return response()->json([$admin,$adminToken]);
        } catch (\Throwable $th) {
          return 'un authorized';
        }
        
      
        // }
        
      
    }
//     public function adminLogin(Request $request)
// {
//     $credentials = $request->validate([
//         'email' => ['required', 'email'],
//         'password' => ['required'],
//     ]);
    
//     if (Auth::guard('admin-api')->once($credentials)) {
//         $admin = Auth::guard('admin-api')->user();
//         return response()->json([$admin]);
//     }
    
//     return 'unauthorized';
// }



public function showUser($id){
    // $id=Auth::user()->id;
    try {
        $user= new UserResourse(User::findOrFail($id));
        return response()->json($user);
       }
     catch (\Exception $ex) {
        return 'user dose not exist';
    }
 }

 public function users(){
  try {
      $user= UserResourse::collection(User::all());
      return response()->json($user);
     }
   catch (\Exception $ex) {
      return 'user dose not exist';
  }
}



 public function adminLogout(Request $request){
    try {
      Auth::user('admin-api')->currentAccessToken()->delete();
  
      return response()->json('logout successfuly');
    } catch (\Throwable $th) {
      return response()->json('some this is wrong');
    }
     
  }

  public function updateUser(UpdateRequest $request,$id){

    $user = User::find($id);
    if ($user->name !== $request->name ) {
      // Attempt to rename the user's folder to the new name
      $old_folder_path = public_path("users/$user->name");
      $new_folder_path = public_path("users/$request->name");
      if (is_dir($old_folder_path)) { // Check if the old folder exists
          if (rename($old_folder_path, $new_folder_path)) { // Attempt to rename the folder
              $user->name = $request->name;
          } else {
              return response()->json(['error' => 'Failed to rename folder.'], 500);
          }
      } else {
          return response()->json(['error' => 'Folder does not exist.'], 404);
      }
    }
    // $user->name=$request->name;
    $user->email=$request->email;
    $user->gender=$request->gender;
    $user->save();
    
    return $user;
}
    

public function delete($id){

  try {
 $user=User::findOrFail($id);
//  return $user;
  $imageName =  $user->imgName;
  unlink(public_path("users/$user->name/$user->imgName"));
  rmdir(public_path("users/$user->name"));
  $user->delete();

  return $user;
  } catch (\Throwable $th) {
     return 'some thing error';
  }
 
}
}
