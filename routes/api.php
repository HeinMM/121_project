<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function(){
    return "hay";
});

Route::post('/userData',[UserController::class,'createCustomer']);

Route::get('/users',[UserController::class,'index'])->middleware('auth:api');

Route::get('/redirect',[UserController::class,'redirect'])->name('login');

Route::post('/login',[UserController::class,'login']);

Route::post('/logout',[UserController::class,'logout']);



////////////////////////////////////////////////////////////////////////////////////



Route::group(['middleware'=>'auth:api'],function(){

    Route::get('/posts',[PostController::class,'index']);



    Route::get('/post/{id}',[PostController::class,'show']);

    Route::post('/posts/{id}',[PostController::class,'update']);

    Route::get('/posts/{id}/delete',[PostController::class,'delete']);



    Route::get('/salesLimit',[SaleController::class,'indexByLimit']);



    Route::get('/sale/{id}',[SaleController::class,'show']);

    Route::get('/sales/{id}/delete',[SaleController::class,'delete']);

    Route::get('/test',[PostController::class,'test']);

    Route::get('/getRecomment',[PostController::class,'recommentShow']);

});


Route::post('/post',[PostController::class,'createPost']);


Route::post('/sale',[SaleController::class,'createSale']);
Route::get('/sales',[SaleController::class,'index']);

Route::post('/image',[SaleController::class,'image']);








