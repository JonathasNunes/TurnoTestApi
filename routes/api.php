<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', 'App\Http\Controllers\AuthController@login');

Route::group(['middleware' => ['apiJwt']], function() {

    Route::post('auth/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('auth/refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('auth/me', 'App\Http\Controllers\AuthController@me');

    Route::get('users', 'App\Http\Controllers\UserController@index');

});