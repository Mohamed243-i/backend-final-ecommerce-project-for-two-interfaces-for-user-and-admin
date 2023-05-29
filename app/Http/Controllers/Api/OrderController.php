<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Stripe;
use  App\Models\UserCard;

class OrderController extends Controller
{
    public function store(Request $request){
        $user_id =Auth::user()->id ;
        $order = new Order();
        $order->name = Auth::user()->name ;
        $order->city=$request->city;
        $order->governate=$request->governate;
        $order->street=$request->street;
        $order->mobile=$request->mobile;
        $order->pinCode=$request->pinCode;
        $order->total_price = $request->amount;
        $order->user_id =Auth::user()->id ;
        $order->save();

        // $productPrice=Product::select('price')->find(7)->price;
        // return $productPrice;
 
        $orderProducts = \App\Models\UserCard::where('user_id', $user_id)->get();
        $orderProducts->makeHidden(['created_at','updated_at','id','user_id']);
        // return ["orderProducts"=>$orderProducts];

      
        $totalPrice=0;
         
        foreach ($orderProducts as $product) {
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product['product_id'];  
            $orderProduct->price=Product::select('price')->find($product['product_id'])->price;
            $orderProduct->quantity = $product['quantity'];
             $totalPrice+=$product['quantity'] * Product::select('price')->find($product['product_id'])->price;
             $order2 = Order::find($order->id);
             $order2->total_price=$totalPrice;
             $order2->save();
             $orderProduct->save();
             $products=Product::find($product['product_id']);
             $quan= $products->quantity;
             $products->quantity=$quan-$product['quantity'];
             $products->save();
        }
 
        if($order && $orderProduct){
            try {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $res = $stripe->tokens->create([
                    'card' => [
                        'number' => $request->number,
                        'exp_month' => $request->exp_month,
                        'exp_year' => $request->exp_year,
                        'cvc' => $request->cvc,
                    ],
                ]);
    
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $response = $stripe->charges->create([
                    'amount' => $request->amount,
                    'currency' => 'usd',
                    'source' => $res->id,
                    'description' => 'payment number',
                ]);
                    $order2 = Order::find($order->id);
                    $order->payment_status='2';
                    $order->save();
                    if($order){
              $orderProducts = UserCard::where('user_id', $user_id)->delete();
                        // foreach ($orderProducts as $product) {
                        //     $orderProducts->delete();
                        // }
                     
                    }  
                return response()->json([$response->status], 201);
            } catch (\Exception $ex) {
                $order->delete();
                return $ex;
                // return response()->json(['response' => 'Error'], 500);
            }
        }
        else{
            return response()->json([['response' => 'Error in payment']], 500); 
        }
        
  
        // return $order->load('orderProducts');
    }



     public function getOrderDetails(){

        $user_id =Auth::user()->id ;
        $orders = Order::where('user_id',$user_id)->with('orderProducts.product.images')->get()->last();
        return response()->json($orders);
     }

     public function getAllOrderForUser(){

        $user_id =Auth::user()->id ;
        $orders = Order::where('user_id',$user_id)->get();
        return response()->json($orders);
     }

     public function getOrderDetailForUser($id){

        $user_id =Auth::user()->id ;
        $orders = Order::where('user_id',$user_id)->where('id',$id)->with('orderProducts.product.images')->first();
        return response()->json($orders);
     }

     public function getAllOrders(){
         
        $orders = Order::with(['user'=>function($q){
        return $q->select('id','email');
        }])->get();
        return response()->json($orders);
     }

     public function getOrderDetailById($id){
         
        $orderDeatil = OrderProduct::with(['order'=>function($q){
            return $q->select('id','payment_status');
        }])->where('order_id',$id)->with('product.images')->get();
        //   $status=Order::where('id',$id)->select('payment_status')->first();
          
        return response()->json($orderDeatil);
     }

     public function getAllOrderDetails(){
        $orders = Order::with('orderProducts.product.images')->get()->toArray();
        return response()->json($orders);
     }
      
    

       public function changeOrderStatus(Request $request,$id){
        $order=Order::findOrfail($id);
         $status = $request->status;
        if($order->payment_status <4 && $status <=3){
            $order->payment_status = $status +1;  
            $order->save();
            return response()->json($order->payment_status);  
        }
        else{
            return response()->json(['error']);
        }
     }




     
     public function addAddress(Request $request)
     {  
        $user_id=1;
        $order=Order::where('user_id',$user_id)->latest()->first();
        $order->city=$request->city;
        $order->governate=$request->governate;
        $order->street=$request->street;
        $order->pinCode=$request->pinCode;
        $order->mobile=$request->mobile;
        $order->save();
        return $order;
     }

}






