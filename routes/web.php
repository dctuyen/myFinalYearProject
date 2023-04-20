<?php

use Illuminate\Support\Facades\Auth;
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
Auth::routes();


Route::redirect('/', 'login');
Route::get('/forgotpassword', function () {
    return view('auth/passwords/email');
});


//Auth route
Route::get('login',  [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login',  [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'new'])->name('new');

Route::get('/activation/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'activateUser'])->name('activation');
