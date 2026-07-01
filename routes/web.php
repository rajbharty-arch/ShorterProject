<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
Route::get('pageNotFound',function(){
    return "<h1>Page Not Found.</h1>";
  })->name("pageNotFound");

Route::get('/', function () {
    return view('login');
})->name('loginViewPage');


Route::controller(UserController::class)->group(function () {
  Route::post('/login','login')->name('login');
  Route::get('/shortUrl/{url?}','shortUrl')->name('shortUrl');
});


Route::middleware(AuthMiddleware::class)
->controller(UserController::class)->group(function () {
Route::post('/logout','logout')->name('logout');
Route::get('/invite','invite')->name('invite');
Route::post('/addInvite','addInvite')->name('addInvite');
Route::get('/dashboard','dashboard')->name('dashboard');
Route::post('/addURL','addURL')->name('addURL');
});


