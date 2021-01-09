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
    return 'Welcome Car api............';
});

Route::get('google/redirect', [App\Http\Controllers\AuthController::class, 'redirectToGoogle']);
Route::get('google/callback', [App\Http\Controllers\AuthController::class, 'googleCallback']);
