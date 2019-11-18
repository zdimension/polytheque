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

include_once("auth.php");

Route::get('/', "HomeController@index")->name("root");

Route::get('/legal', function() {
    return view("legal");
})->name("legal");

Route::group(['middleware' => 'auth'], function ()
{
    Route::prefix("/compte")->name("compte.")->group(function ()
    {
        Route::get('/', 'UtilisateurController@voirProfil')->name("view");
        Route::patch('/', 'UtilisateurController@modifierProfil')->name("edit");
    });

    Route::group(['middleware' => 'verified'], function()
    {
        Route::prefix("/cursus")->name("cursus.")->group(function ()
        {
            Route::get('/', 'CursusController@index')->name("list");
            Route::get('/effacer', 'CursusController@effacer')->name("clearForm");
            Route::put('/ajouter', 'CursusController@ajouter')->name("add");
        });

        Route::prefix("/edt")->name("edt.")->group(function ()
        {
            Route::get('/', 'EDTController@index')->name("index");
            Route::get('/recherche/{query}', 'EDTController@search')->name("search");
        });
    });
});
