<?php
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

//Route::group(['middleware' => 'auth'], function ()
//{
    Route::get('/', "HomeController@index")->name("root");


    Route::get('/logout', 'Auth\LoginController@logout');

    Route::prefix("/compte")->name("compte.")->group(function ()
    {
        Route::get('/', 'UserController@voirProfil')->name("view");
        Route::patch('/', 'UserController@modifierProfil')->name("edit");
    });
//});
