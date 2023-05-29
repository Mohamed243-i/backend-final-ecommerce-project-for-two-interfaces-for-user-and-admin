<?php

namespace App\Http\Controllers\Api\products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\ProductResource;
use App\Http\Requests\products\StoreProductRequest;

class ProductController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:admin-api')->except(['show','index','searchByProductName','searchByCatagoryName']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::with('images')->get();

            $data = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'description' => $product->description,
                    'rate' => $product->rate,
                    'discount' => $product->discount,
                    'quantity' => $product->quantity,
                    'category'=>$product->category->name,
                    'images' => $product->images->pluck('imgPath')
                ];
            });
            
            return response()->json($data);
        } catch (\Throwable $th) {
            return $th;

            return 'some this is error';
        }
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       try {
        $product=new Product();
        $product->name=$request->name;
        $product->rate=$request->rate;
        $product->price=$request->price;
        $product->quantity=$request->quantity;
        $product->description=$request->description;
        $product->discount=$request->discount;
        $product->status=$request->status;
        $product->category_id=$request->category_id;
        $product->save();

        if ($request->hasFile('image')) {
        
        //    $images= $request->hasFile('image')
                    $url = "http://127.0.0.1:8000/productImages/";
                   
                    
                    foreach ($request->file('image') as $image) {
                 
                        if (!$image->isValid()) {
                            // The image is not valid, handle the error appropriately
                            continue;
                        }
                        
                        $imageName = $image->getClientOriginalName();
                        $fullPath = $url . $imageName;
                        $newImage = new Image();
                        $newImage->product_id = $product->id;
                        $newImage->imgPath = $fullPath;
                        $newImage->name = $imageName;
                        $newImage->save();
                        $image->move(public_path('productImages'), $imageName);

                    }
                
                return response()->json([$product,'success' => true]);

            }
       } catch (\Throwable $th) {
        return response()->json($th);
       }
       
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $product=Product::find($id);
        $product= new ProductResource(Product::findOrFail($id));

        return response()->json($product);
    }
    public function searchByProductName(Request $request,$pronName)
    {
        // $product=Product::where("name",$name)->get();
        // $search=$request->name;
        $product= ProductResource::collection(Product::with('images')->where('name','LIKE',"%{$pronName}%")->get());
        return response()->json($product);
    }

   

    public function searchByCatagoryName(Request $request)
    {
      try{
        $catName=$request->catName;
        // return $catName;
        $category = Category::where('name', $catName)->first();

        $products = Product::with('images')->whereHas('category', function($query) use ($category) {
            $query->where('id', $category->id);
        })->get();

         $products= ProductResource::collection($products);
        return response()->json($products);
      } catch (\Throwable $th) {
        return response()->json('category not exist');
      }
     

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update($id,Request $request)
    // {
    //     $product= Product::find($id);
    //     $product->name=$request->name;
    //     $product->rate=$request->rate;
    //     $product->price=$request->price;
    //     $product->quantity=$request->quantity;
    //     $product->description=$request->description;
    //     $product->discount=$request->discount;
    //     $product->status=$request->status;
    //     $product->category_id=$request->category_id;
    //     $product->save();

    //     // $oldImages = Image::where('product_id', $id)->get();
    //     // $oldImages = Product::find($id)->images;
    //      $oldImages = $product->find($id)->images;
    //     foreach($oldImages as $oldImage){
    //         unlink(public_path("productImages/$oldImage->name"));
    //         $oldImage->delete();
    //     }
       
    //     if ($request->hasFile('image')) {

    //                     $url = "http://127.0.0.1:8000/productImages/";
    //                     foreach ($request->file('image') as $image) {
                            
    //                         $imageName = $image->getClientOriginalName();
    //                         $image->move(public_path('productImages'), $imageName);
    //                         $fullPath = $url . $imageName;
    //                         $newImage = new Image();
    //                         $newImage->product_id = $product->id;
    //                         $newImage->imgPath = $fullPath;
    //                         $newImage->name = $imageName;
    //                         $newImage->save();
    //                     }   
    //             }

    //             return response()->json([$product,'success' => true]);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
        $product=Product::find($id);
        $images=Product::find($id)->images;
        
        foreach($images as $image){     
                if(file_exists("productImages/$image->name"));
                {
                unlink(public_path("productImages/$image->name"));
                }
        }
        $product->delete();
        return 'deleted successfuly';
    }

        catch (\Throwable $th) {
            return "some thisng is wrong";
           }
    }
}





