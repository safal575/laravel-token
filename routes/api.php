<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\HotelController;
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
Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('hotels', [HotelController::class, 'index']);
    Route::get('hotels/{id}', [HotelController::class, 'show']);
    Route::post('create', [HotelController::class, 'store']);
    Route::put('update/{hotel}',  [HotelController::class, 'update']);
    Route::delete('delete/{hotel}',  [HotelController::class, 'destroy']);
});
