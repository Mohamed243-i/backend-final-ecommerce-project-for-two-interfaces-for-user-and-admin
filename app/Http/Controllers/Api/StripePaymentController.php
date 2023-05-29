<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe;

class StripePaymentController extends Controller
{
   
    public function stripePost(Request $request)
    {

       
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
                'description' => $request->description,
            ]);
            $order=\App\Models\Order::where('user_id',1)->latest()->first();
            $order->payment_status='paid';
            $order->save();
           
            // $order->update(['statue'=>'paid']);
            //  $lastOrder = DB::table('orders')->orderBy('created_at', 'desc')->limit(1);
            return response()->json([$response->status], 201);
        } catch (Exception $ex) {
            return response()->json([['response' => 'Error']], 500);
        }
    }

   
}




// catch(\Stripe\Exception\CardException $e) {
//     // Since it's a decline, \Stripe\Exception\CardException will be caught
//     echo 'Status is:' . $e->getHttpStatus() . '\n';
//     echo 'Type is:' . $e->getError()->type . '\n';
//     echo 'Code is:' . $e->getError()->code . '\n';
//     // param is '' in this case
//     echo 'Param is:' . $e->getError()->param . '\n';
//     echo 'Message is:' . $e->getError()->message . '\n';
//   } catch (\Stripe\Exception\RateLimitException $e) {
//     // Too many requests made to the API too quickly
//   } catch (\Stripe\Exception\InvalidRequestException $e) {
//     // Invalid parameters were supplied to Stripe's API
//   } catch (\Stripe\Exception\AuthenticationException $e) {
//     // Authentication with Stripe's API failed
//     // (maybe you changed API keys recently)
//   } catch (\Stripe\Exception\ApiConnectionException $e) {
//     // Network communication with Stripe failed
//   } catch (\Stripe\Exception\ApiErrorException $e) {
//     // Display a very generic error to the user, and maybe send
//     // yourself an email
//   } catch (Exception $e) {
//     // Something else happened, completely unrelated to Stripe
//   }
