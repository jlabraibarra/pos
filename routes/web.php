<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('pass/{str}', function ($str) {
    return $str . " => " . Hash::make($str);
});

Route::get('/', function () {
    return view('welcome',[
        "title" => "Login"
    ]);
})->name("login");


Route::prefix("login")->group( function(){
    Route::post('/access', [LoginController::class, 'login']);
    Route::get('/logout', [LoginController::class, 'logout']);
});

Route::prefix("pos")->group( function(){
    Route::get('/', [PosController::class, 'index'])->middleware('auth');
    Route::get('/getProductsDialog', [PosController::class, 'getProductsDialog'])->middleware('auth');
    Route::get('/setFav', [PosController::class, 'setFavorite'])->middleware('auth');
    Route::get('/getFavs', [PosController::class, 'getFavorites'])->middleware('auth');


    Route::prefix("product")->group( function(){
        Route::get('/', [PosController::class, 'getProducts'])->middleware('auth');
        Route::post('/data', [PosController::class, 'getDataProd'])->middleware('auth');
        Route::post('/save', [PosController::class, 'saveProduct'])->middleware('auth');
        Route::post('/delete', [PosController::class, 'deleteProduct'])->middleware('auth');
    });

    Route::prefix("inventory")->group( function(){
        Route::get('/', [PosController::class, 'getInventory'])->middleware('auth');
    });
});
