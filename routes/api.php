<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RouteController;

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
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {
    Route::get('cities/', [CityController::class, 'index']);
    Route::post('cities', [CityController::class, 'store'])->middleware('checkadmin');
    Route::get('cities/{city}', [CityController::class, 'show']);
    Route::put('cities/{city}', [CityController::class, 'update'])->middleware('checkadmin');
    Route::delete('cities/{city}', [CityController::class, 'delete'])->middleware('checkadmin');
    Route::get( 'search-city', [CityController::class, 'searchCity']);
    
    Route::post( 'import-airports', [ImportController::class, 'importAirports'])->middleware('checkadmin');
    Route::post( 'import-routes', [ImportController::class, 'importRoutes'])->middleware('checkadmin');

    Route::resource('comment', CommentController::class);

    Route::get( 'find-routes', [RouteController::class, 'findRoutes']);
});