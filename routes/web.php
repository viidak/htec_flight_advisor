<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RouteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', ['adminRole' => User::ADMIN_ROLE]);
})->middleware(['auth'])->name('dashboard');

Route::post( 'city/add', [CityController::class, 'addCity'])->name('city.add');
Route::get( 'city/search', [CityController::class, 'search'])->name('citySearch');
Route::post('city/addComment', [CityController::class, 'addComment'])->name('addComment');
Route::resource('comments', CommentController::class);
Route::get('route/search', [RouteController::class, 'search'])->name('routeSearch');

Route::post( 'import/', [ImportController::class, 'import'])->name('importData');

require __DIR__.'/auth.php';
