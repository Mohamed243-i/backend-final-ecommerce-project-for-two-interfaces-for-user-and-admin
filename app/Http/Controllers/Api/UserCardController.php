<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCard;
use App\Models\Product;
use App\Models\Favorite;
use Exception;
use App\Models\Image;
use Illuminate\Support\Facades;


class UserCardController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' =>  'required|max:255|unique:user_cards,product_id,null,id,user_id,'.Auth::user()->id,
        ]);
        try {
        $userCard=new UserCard();
        $userCard->user_id=Auth::user()->id;
        $userCard->product_id=$request->product_id;
        $userCard->save();

        return response()->json($userCard);
        } catch (\Exception $e) {
        //   throw new Exception("Error Processing Request");
        // return $e->getMessage();
        return 'prpduct noT available';

        }

    }

    public function deleteFromCart($product_id){
        $product=UserCard::where('product_id',$product_id)->first();
        $product->delete();
        return 'deleted successfuly';

    }

    public function showUserCard(){

        try {
            $user_id = auth()->id();
        // Retrieve all cards associated with the logged-in user
        $cards = UserCard::where('user_id', $user_id)->get();
 
        foreach ($cards as $card) {
            $quantityCart = $card ? $card->quantity : 0;
            // dd($quantityCart);
            $product = $card->product;
            $product['quantityCart']=$quantityCart;
            $product->makeHidden(['created_at','updated_at','image']);
            $imagePaths = $product->images()->select('imgPath')->get()->pluck('imgPath')->toArray();    
            // Add the product and image data to the array

            $product->imagePaths = $imagePaths;
          
            $data[] = $product;
            // $data[]=$quantityCart;
        }
       
        // Return the data as a JSON response
        return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(["error"=>"no data in card"]);
        }
      

    }

   public function transformToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|max:255|unique:user_cards',
    ]);

    try {
        $favorite = Favorite::where('product_id', $request->product_id)
        ->where('user_id', Auth::id())
        ->first();
        
       
    if ($favorite) {
        // return $favorite;
        $cart = new UserCard();
        $cart->product_id = $request->product_id;
        $cart->user_id = Auth::id();
        // $cart->quantity = $request->quantity;
        $cart->save();

        $favorite->delete();

        return response()->json(['success' => true], 200);

    } 
    else {
        return response()->json(['error' => 'Product not found'], 404);

    }
    } catch (\Throwable $th) {
        return response()->json(['error' => 'Product not found'], 404);

    }

}

public function increaseQuantity(Request $request){

    $user_id = auth()->id();
    $product_id=$request->product_id;
    // return $product_id;
    $card=UserCard::where('user_id',$user_id)->where('product_id',$product_id)->first();
    $productQuan=Product::select('quantity')->find($product_id)->quantity;
    $quantity = $card ? $card->quantity : 0;
    if($quantity>=$productQuan){
        return " quantity not available ";
    }
     else if($quantity>0 && $quantity < 10  && $quantity<$productQuan ){
        $card->quantity=$quantity+1;
        $card->save();
    }  
    return response()->json(['incresse successfuly',$card]);
}

public function decreaseQuantity(Request $request){

    $user_id = auth()->id();
    $product_id=$request->product_id;
    // return $product_id;
    $card=UserCard::where('user_id',$user_id)->where('product_id',$product_id)->first();
    
    // $productQuan=Product::select('quantity')->find($product_id)->quantity;
    
    $quantity = $card ? $card->quantity : 0;
    // return $productQuan.$quantity;

    if($quantity<=1){
        return " product not available ";
    }
     else if($quantity>0 && $quantity < 10 ){
        $card->quantity=$quantity-1;
        $card->save();
    }
    
      
    return response()->json(['decrease successfuly',$card]);


}


    

}
