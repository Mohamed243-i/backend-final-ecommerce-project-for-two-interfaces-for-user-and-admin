<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\UserCard;
use App\Models\Favorite;
use Exception;

class FavoriteController extends Controller
{
    public function addToFavorite(Request $request)
    {
        $request->validate([
            'product_id' => 'required|max:255|unique:favorites',
        ]);
        try {
        $favorite=new Favorite();
        $favorite->user_id=Auth::user()->id;
        $favorite->product_id=$request->product_id;
        $favorite->save();

        return response()->json($favorite);
        } catch (\Exception $e) {
        //   throw new Exception("Error Processing Request");
        return $e->getMessage();
        // return 'prpduct noT available';

        }

    }

    public function showFavorite(){

        $user_id = auth()->id();
        // Retrieve all cards associated with the logged-in user
        $favorites = Favorite::where('user_id', $user_id)->get();
    
        // Retrieve the products and images for each card
        foreach ($favorites as $favorite) {
            $product = $favorite->product;
            $imagePaths = $product->images()->select('imgPath')->get()->pluck('imgPath')->toArray();    
            // Add the product and image data to the array

            $product->imagePaths = $imagePaths;
            $data[] = 
                // 'product' => $product,
                  $product
                // 'images' => $imagePaths,
            ;
        }
    
        // Return the data as a JSON response
        return response()->json($data);

 }

 public function deleteFromFavorite($product_id){
    $product=Favorite::where('product_id',$product_id)->first();
    $product->delete();
    // return $product;
    return 'deleted successfuly';

}
}
