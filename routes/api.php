<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Models\ProductSale;
use App\Models\Purchase;
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

Route::group(['middleware' => 'jwt.auth'], function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);    
    Route::put('/edit/{id}',[AuthController::class, 'editOwnProfile']);
});

// ROUTES FOR ADMIN
Route::group(['middleware' => ['jwt.auth', 'isSuperAdmin']], function ()
{
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
    Route::put('/user/editstatus/{id}',[StatusController::class, 'editStatusById']);
});

// ROUTES FOR PRODUCTS
Route::get('/getallproducts', [ProductController::class, 'getAllProducts']);
Route::get('/getproductsbybrand', [ProductController::class, 'getProductsByBrand']);
Route::get('/getproductsbyname', [ProductController::class, 'getProductsByName']);
Route::get('/getproductbyid/{id}', [ProductController::class, 'getProductById']);

// ROUTES FOR SALES
Route::group(['middleware' =>'jwt.auth'], function()
{
    Route::get('/getownsales', [SaleController::class, 'getOwnSales']);
    Route::get('/getownsalesbyid/{id}', [SaleController::class, 'getOwnSalesById']);
});

// ROUTES FOR PURCHASES 
Route::group(['middleware' => 'jwt.auth'], function()
{
    Route::post('/createpurchase', [PurchaseController::class, 'createPurchase']);
});

// ROUTE FOR RATING
Route::group(['middleware' => 'jwt.auth'], function ()
{
    Route::put('/ratesale/{id}',[RatingController::class, 'rateSaleById']);
});