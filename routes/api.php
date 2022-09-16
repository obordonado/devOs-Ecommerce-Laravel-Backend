<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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


//// ROUTES FOR USERS ////
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);    
    Route::put('/edit/{id}',[AuthController::class, 'editOwnProfile']);
});

Route::group(['middleware' => ['jwt.auth', 'isSuperAdmin']], function () {
    Route::post('/user/adsupad/{id}', [UserController::class, 'addSuperAdminRoleToUser']);
    Route::post('/user/remsupad/{id}', [UserController::class, 'removeSuperAdminRoleFromUser']);  
    Route::post('/user/getallusersad/{id}',[UserController::class, 'getRoleUserByAdmin']);
    Route::delete('/user/addelchan/{id}', [ChannelController::class, 'sadminDelChannelById']);
    Route::delete('/user/addelmsg/{id}', [MessageController::class, 'delMessageById']);
    Route::delete('/user/addeluser/{id}', [UserController::class, 'delUserById']);
});


//// ROUTES FOR GAMES ////
Route::get('/getallgames', [GameController::class, 'getAllGames']);
Route::get('/game/{id}', [GameController::class, 'getGameById']);
Route::post('/getgamebytitle', [GameController::class, 'getGameByTitle']);


Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('/creategame', [GameController::class, 'createNewGame']);
    Route::post('/getallgames/{id}', [GameController::class, 'getOwnGamesByUserId']);
    Route::put('/updategame/{id}', [GameController::class, 'updateGameById']);
    Route::delete('/game/delete/{id}', [GameController::class, 'deleteGameById']);
});

//// ROUTES FOR CHANNELS ////
Route::get('/getallchannels', [ChannelController::class, 'getAllChannels']);
Route::get('/channel/{id}', [ChannelController::class, 'getChannelById']);
Route::get('/channelbyname',[ChannelController::class, 'getChannelByName']);

Route::group(['middleware' =>'jwt.auth'], function(){
    Route::post('/channel', [ChannelController::class, 'createChannel']);
    Route::post('/joinchannel/{id}', [ChannelController::class, 'joinChannelById']);
    Route::post('/exitchannel/{id}', [ChannelController::class, 'exitChannelById']);
    Route::put('/channelupdate/{id}', [ChannelController::class, 'updateChannelById']);
});

//// ROUTES FOR MESSAGES ////
Route::get('/messagesbychannelid/{id}', [MessageController::class, 'getMessagesByChannelId']);

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('/createmessage',[MessageController::class, 'createNewMessage']);
    Route::post('/getownmessages', [MessageController::class, 'getOwnMessages']);
    Route::get('/getmsgbymsgid/{id}',[MessageController::class, 'getMessageByMsgId']);
    Route::put('/updatemessage/{id}', [MessageController::class, 'updateMessageByMsgId']);

});