<?php

namespace App\Http\Controllers\Api\products;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;


class UpdateProductController extends Controller
{
    public function updateProduct($id,Request $request)
    { try {
        $product= Product::find($id);
        $product->name=$request->name;
        $product->rate=$request->rate;
        $product->price=$request->price;
        $product->quantity=$request->quantity;
        $product->description=$request->description;
        $product->discount=$request->discount;
        $product->status='avilabile';
        $product->category_id=$request->category_id;
        $product->save();

        // $oldImages = Image::where('product_id', $id)->get();
        // $oldImages = Product::find($id)->images;
         $oldImages = $product->find($id)->images;

      
       
        if ($request->hasFile('image')) {

            foreach($oldImages as $oldImage){
                unlink(public_path("productImages/$oldImage->name"));
                $oldImage->delete();
            }
                        $url = "http://127.0.0.1:8000/productImages/";
                        foreach ($request->file('image') as $image) {   
                            $imageName = $image->getClientOriginalName();
                            $image->move(public_path('productImages'), $imageName);
                            $fullPath = $url . $imageName;
                            $newImage = new Image();
                            $newImage->product_id = $product->id;
                            $newImage->imgPath = $fullPath;
                            $newImage->name = $imageName;
                            $newImage->save();
                        }
                     
                }

                return response()->json([$product,'success' => true]);
    } catch (\Throwable $th) {
        // return $th;
        return response()->json($th);

    }
       
    }
}
