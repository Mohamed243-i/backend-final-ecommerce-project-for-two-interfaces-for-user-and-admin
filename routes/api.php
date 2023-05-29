<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\Api\User\AuthController;
use  App\Http\Controllers\Api\User\ResetPasswordController;
use  App\Http\Controllers\Api\User\ForgetPasswordController;
use  App\Http\Controllers\Api\Admin\AdminAuthController;
use  App\Http\Controllers\Api\OrderController;
use  App\Http\Controllers\Api\products\ProductController;
use  App\Http\Controllers\Api\products\UpdateProductController;
use  App\Http\Controllers\Api\UserCardController;
use  App\Http\Controllers\Api\FavoriteController;
use  App\Http\Controllers\Api\CategoryController;
use  App\Http\Controllers\Api\StripePaymentController;
use  App\Http\Controllers\Api\MessageController;
use  App\Http\Controllers\Api\MessageResponse;
use  App\Http\Controllers\Api\HasShownController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return Auth::user();
});

Route::middleware('auth:admin-api')->get('/admin', function (Request $request) {
    return Auth::user('admin-api');
});
Route::get('test', function () {
    return 'test';
})->middleware('auth:admin-api');
//////////users/////////

Route::group(['prefix' => 'users'], function () {

    Route::get('show', [AuthController::class, 'show']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'userLogout'])->middleware('auth:api');


    ////////////////////////////////////////////////////////  //

    Route::post('resetPassword', [ResetPasswordController::class, 'resetPassword']);
    Route::post('forgetPassword', [ForgetPasswordController::class, 'forgetPassword']);

    ///////////////////////////////////////////
    //  Route::put('update/{id}',[AuthController::class,'update']);   //

    Route::post('login', [AuthController::class, 'login']);         //
    Route::post('addToCart', [UserCardController::class, 'addToCart'])->middleware('auth:api');   //
    Route::get('showUserCard', [UserCardController::class, 'showUserCard'])->middleware('auth:api');  //
    Route::delete('deleteFromCart/{productId}', [UserCardController::class, 'deleteFromCart']);        //
    Route::post('addToFavorite', [FavoriteController::class, 'addToFavorite'])->middleware('auth:api');  //
    Route::get('showFavorite', [FavoriteController::class, 'showFavorite'])->middleware('auth:api');    //
    Route::delete('deleteFromFavorite/{productId}', [FavoriteController::class, 'deleteFromFavorite'])->middleware('auth:api');
    Route::post('update', [AuthController::class, 'updateUser'])->middleware('auth:api');
    Route::post('transformToCart', [UserCardController::class, 'transformToCart'])->middleware('auth:api');
    Route::post('increaseQuantity', [UserCardController::class, 'increaseQuantity'])->middleware('auth:api');
    Route::post('decreaseQuantity', [UserCardController::class, 'decreaseQuantity'])->middleware('auth:api');




    //stripement



    Route::post('stripe', [StripePaymentController::class, 'stripePost']);

    ///// /////////////////////////order///////////////////
    Route::post('createorder', [OrderController::class, 'store'])->middleware('auth:api');
    Route::get('getAllOrderForUser', [OrderController::class, 'getAllOrderForUser'])->middleware('auth:api');
    Route::get('getOrderDetailForUser/{id}', [OrderController::class, 'getOrderDetailForUser'])->middleware('auth:api');



    Route::post('userAddress', [OrderController::class, 'addAddress']);
    Route::get('getOrderDetails', [OrderController::class, 'getOrderDetails'])->middleware('auth:api');
    //   Route::get('getOrderDetails',[OrderController::class,'getOrderDetails']);
    Route::get('getAllOrderDetails', [OrderController::class, 'getAllOrderDetails']);
});



/////////////////////////////admin////////////////
Route::post('adminLogin', [AdminAuthController::class, 'adminLogin']);
Route::post('adminLogout', [AdminAuthController::class, 'adminLogout'])->middleware('auth:admin-api');
Route::get('users', [AdminAuthController::class, 'users']);
Route::get('showUser/{id}', [AdminAuthController::class, 'showUser']);
Route::post('updateUser/{id}', [AdminAuthController::class, 'updateUser'])->middleware('auth:admin-api');
Route::delete('delete/{id}', [AdminAuthController::class, 'delete'])->middleware('auth:admin-api');

Route::get('getAllOrders', [OrderController::class, 'getAllOrders'])->middleware('auth:admin-api');
Route::get('getOrderDetailById/{id}', [OrderController::class, 'getOrderDetailById'])->middleware('auth:admin-api');
Route::post('changeOrderStatus/{id}', [OrderController::class, 'changeOrderStatus'])->middleware('auth:admin-api');





///////////////////products/////////////////////////
// Route::apiResource('products',ProductController::class)->middleware('auth:admin-api');
Route::apiResource('products', ProductController::class);

Route::get('searchByProductName/{proName}', [ProductController::class, 'searchByProductName']);
Route::get('searchByCatagoryName/{catName}', [ProductController::class, 'searchByCatagoryName']);
Route::post('product/update/{id}', [UpdateProductController::class, 'updateProduct'])->middleware('auth:admin-api'); //updateProduct



// Route::group(['prefix'=>'products','as'=>'account.'], function(){
//     Route::get('/', 'AccountController@index')->name('index');
//     Route::get('connect', 'AccountController@connect')->name('connect');
// });


////////////////////////////////////////////////////////
Route::apiResource('categories', CategoryController::class);
Route::apiResource('messages', \App\Http\Controllers\Api\MessageController::class);

////////////////////////////////////////////////////////
Route::get('messages', [MessageController::class, 'showMessages'])->middleware('auth:admin-api');
Route::post('messages', [MessageController::class, 'sendMessage'])->middleware('auth:api');

Route::post('sendResponse/{user_id}', [MessageResponse::class, 'sendResponse'])->middleware('auth:admin-api');
Route::get('showResponse', [MessageResponse::class, 'showResponse'])->middleware('auth:api');
//////////////////////////////////////////////////////////
Route::post('ShownByUser',[HasShownController::class,'ShownByUser'])->middleware('auth:api');
Route::post('ShownByAdmin',[HasShownController::class,'ShownByAdmin'])->middleware('auth:admin-api');