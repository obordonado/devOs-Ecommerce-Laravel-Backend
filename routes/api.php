<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


// ROUTES FOR USERS 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);    
    Route::put('/edit/{id}',[AuthController::class, 'editOwnProfile']);
});

Route::group(['middleware' => ['jwt.auth', 'isSuperAdmin']], function () {
    Route::post('/user/addsuperadmin/{id}', [UserController::class, 'addSuperAdminRoleToUser']);
    Route::post('/user/removesuperadmin/{id}', [UserController::class, 'removeSuperAdminRoleFromUser']);  
    Route::get('/user/getallusersbyadmin/{id}',[UserController::class, 'getRoleUserByAdmin']);//Gets all users that are not admin or superadmin.
    Route::get('/user/getallsalesbyadmin', [SaleController::class, 'getAllSalesBySuperAdmin']);
    Route::delete('/user/deleteuserby/{id}', [UserController::class, 'delUserById']);
    Route::post('/user/createproduct', [ProductController::class, 'createProduct']);
    Route::put('/user/editproduct/{id}',[ProductController::class, 'editProductById']);
    Route::delete('/user/deleteproduct/{id}', [ProductController::class, 'deleteProductById']);
    // Route::delete('/deletepurchasebyid{id}', [SaleController::class, 'deletePurchaseById']); Not active due to the nature of the product. (No refunds allowed). Kept for scalability.
    Route::get('/user/getsalesbyuserid/{id}', [SaleController::class, 'getSaleByUserId']);
    Route::get('/user/getsalebyid/{id}',[SaleController::class, 'getSaleById']);
    Route::get('/user/getsalesbystatus', [UserController::class, 'getSalesByStatus']);
});


// ROUTES FOR PRODUCTS
Route::get('/getallproducts', [ProductController::class, 'getAllProducts']);
Route::get('/getproductsbybrand', [ProductController::class, 'getProductsByBrand']);
Route::get('/getproductsbyname', [ProductController::class, 'getProductsByName']);
Route::get('/getproductbyid/{id}', [ProductController::class, 'getProductById']);



// ROUTES FOR SALES
Route::group(['middleware' =>'jwt.auth'], function(){
    Route::post('/createpurchase', [SaleController::class, 'createPurchase']);
    Route::get('/getownpurchases', [SaleController::class, 'getOwnPurchases']);
    Route::get('/getownpurchasesbyid/{id}', [SaleController::class, 'getOwnPurchasesById']);
});


// ROUTES FOR PRODUCT_SALE 
Route::get('/messagesbychannelid/{id}', [MessageController::class, 'getMessagesByChannelId']);

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('/createmessage',[MessageController::class, 'createNewMessage']);
    Route::post('/getownmessages', [MessageController::class, 'getOwnMessages']);
    Route::get('/getmsgbymsgid/{id}',[MessageController::class, 'getMessageByMsgId']);
    Route::put('/updatemessage/{id}', [MessageController::class, 'updateMessageByMsgId']);

});





// ROUTES FOR MESSAGES 
Route::get('/messagesbychannelid/{id}', [MessageController::class, 'getMessagesByChannelId']);

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('/createmessage',[MessageController::class, 'createNewMessage']);
    Route::post('/getownmessages', [MessageController::class, 'getOwnMessages']);
    Route::get('/getmsgbymsgid/{id}',[MessageController::class, 'getMessageByMsgId']);
    Route::put('/updatemessage/{id}', [MessageController::class, 'updateMessageByMsgId']);

});