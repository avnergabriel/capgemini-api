<?php

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

Route::POST('/login', 'AccountController@login');
Route::POST('/register', 'AccountController@register');
Route::POST('/check-login', 'AccountController@checkLogin');

Route::GET('/transaction/{hash}', 'TransactionController@index');
Route::POST('/transaction', 'TransactionController@store');
