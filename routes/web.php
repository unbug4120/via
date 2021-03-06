<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/api/home', [App\Http\Controllers\HomeController::class, 'show']);

/*
 * Clients management
 * */
// Route::prefix('/api/clients')->group(function () {
//     Route::get('/', [\App\Http\Controllers\ClientsController::class, 'index']);
//     Route::get('/{client}', [\App\Http\Controllers\ClientsController::class, 'show']);
//     Route::post('/store', [\App\Http\Controllers\ClientsController::class, 'store']);
//     Route::patch('/{client}', [\App\Http\Controllers\ClientsController::class, 'update']);
//     Route::post('/destroy', [\App\Http\Controllers\ClientsController::class, 'destroyMass']);
//     Route::delete('/{client}/destroy', [\App\Http\Controllers\ClientsController::class, 'destroy']);
// });

/*
 * Via management
 * */
Route::prefix('/api/via')->group(function () {
    Route::get('/', [\App\Http\Controllers\ViaController::class, 'index']);
    Route::get('/{via}', [\App\Http\Controllers\ViaController::class, 'show']);
    Route::post('/store', [\App\Http\Controllers\ViaController::class, 'store']);
    Route::post('/{via}/buy', [\App\Http\Controllers\ViaController::class, 'buy']);
    Route::patch('/{via}', [\App\Http\Controllers\ViaController::class, 'update']);
    Route::post('/destroy', [\App\Http\Controllers\ViaController::class, 'destroyMass']);
    Route::delete('/{via}/destroy', [\App\Http\Controllers\ViaController::class, 'destroy']);
});
Route::prefix('/api/viapanel')->group(function () {
    Route::get('/{via}', [\App\Http\Controllers\ViaPanelController::class, 'show']);
    Route::get('/', [\App\Http\Controllers\ViaPanelController::class, 'index']);
});
/*
 * Current user
 * */
Route::prefix('api/user')->group(function () {
    Route::get('/', [\App\Http\Controllers\CurrentUserController::class, 'show']);
    Route::patch('/', [\App\Http\Controllers\CurrentUserController::class, 'update']);
    Route::patch('/password', [\App\Http\Controllers\CurrentUserController::class, 'updatePassword']);
});

Route::prefix('api/tool')->group(function () {
    Route::post('hotmail/check', [\App\Http\Controllers\ToolController::class, 'checkHotMail']);
});

/*
 * File upload (e.g. avatar)
 * */
Route::post('/files/store', [\App\Http\Controllers\FilesController::class, 'store']);
