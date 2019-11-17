<?php

use Illuminate\Support\Facades\Route;

Route::get("/connexion", 'Auth\LoginController@showLoginForm')->name('login');
Route::post("/connexion", 'Auth\LoginController@login');
Route::get("/deconnexion", 'Auth\LoginController@logout')->name('logout');

Route::get("/inscription", 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post("/inscription", 'Auth\RegisterController@register');

Route::prefix("/mdp")->name("password.")->group(function ()
{
    Route::get('/reinitialiser', 'Auth\ForgotPasswordController@showLinkRequestForm')->name("request");
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name("email");
    Route::get('/reinitialiser/{token}', 'Auth\ResetPasswordController@showResetForm')->name("reset");
    Route::post('/reinitialiser', 'Auth\ResetPasswordController@reset')->name("update");
});

Route::prefix("/email")->name("verification.")->group(function ()
{
    Route::get('/verifier', 'Auth\VerificationController@show')->name('notice');
    Route::get('/verifier/{id}', 'Auth\VerificationController@verify')->name('verify');
    Route::get('/reenvoyer', 'Auth\VerificationController@resend')->name('resend');
});
