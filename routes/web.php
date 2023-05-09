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


/* Start Auth Route */

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'new'])->name('new');

Route::get('forgotpassword', [App\Http\Controllers\Auth\LoginController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('confirmrequest', [App\Http\Controllers\Auth\LoginController::class, 'confirmrequest'])->name('confirmrequest');

Route::get('resetpassword/{userid}/{token}', [App\Http\Controllers\Auth\LoginController::class, 'resetpassword'])->name('resetpassword');
Route::post('submitpassword', [App\Http\Controllers\Auth\LoginController::class, 'submitpassword'])->name('submitpassword');

Route::get('/activation/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'activateUser'])->name('activation');

/* End Auth Route */

Route::group(['prefix' => 'admin'], function () {
    Route::get('home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.home');
    Route::get('studentmanagement', [App\Http\Controllers\Admin\HomeController::class, 'studentManagement'])->name('admin.studentmanagement');
})->middleware('isAdmin');

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('myaccount', [App\Http\Controllers\HomeController::class, 'myaccount'])->name('myaccount');
Route::post('editaccount', [App\Http\Controllers\HomeController::class, 'editAccount'])->name('editaccount');

Route::any('{url}', static function(){
    return view('errors.404')->with(['uinfo' => auth()->user()]);
})->where('url', '.*');
