<?php

use App\Http\Controllers\Admin\AdminController;
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
    Route::get('courses', [AdminController::class, 'coursemanagement'])->name('admin.coursemanagement');
    Route::get('course/new', [AdminController::class, 'course'])->name('admin.newcourse');
    Route::get('course/edit/{id}', [AdminController::class, 'course'])->name('admin.editcourse');
    Route::delete('course/delete/{id}', [App\Http\Controllers\Admin\HomeController::class, 'deletecourse'])->name('admin.deletecourse');
    Route::post('savecourse', [App\Http\Controllers\Admin\AdminController::class, 'saveCourse'])->name('admin.savecourse');

    Route::get('maketestdata', [App\Http\Controllers\Admin\HomeController::class, 'createDataUser'])->name('maketestdata');
    Route::delete('deleteaccount/{userid}', [App\Http\Controllers\HomeController::class, 'deleteAccount'])->name('admin.deleteaccount');
})->middleware('isAdmin');

Route::match(['get'], 'home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get( 'createaccount', [App\Http\Controllers\HomeController::class, 'new'])->name('createaccount');
Route::get( 'account', [App\Http\Controllers\HomeController::class, 'new'])->name('account');
Route::get( 'account/my', [App\Http\Controllers\HomeController::class, 'new'])->name('myaccount');
Route::get('account/edit/{id}', [App\Http\Controllers\HomeController::class, 'new'])->name('admin.editaccount');

/* Student info */
Route::get('student', [App\Http\Controllers\HomeController::class, 'usermanagement'])->name('studentmanagement');
Route::get('newstudent', [App\Http\Controllers\HomeController::class, 'new'])->name('admin.newstudent');

/* Teacher info */
Route::get('teacher', [App\Http\Controllers\HomeController::class, 'usermanagement'])->name('teachermanagement');
Route::get('newteacher', [App\Http\Controllers\HomeController::class, 'new'])->name('admin.newteacher');

/* Save info */
Route::get('carer', [App\Http\Controllers\HomeController::class, 'usermanagement'])->name('carermanagement');
Route::post('editaccount', [App\Http\Controllers\HomeController::class, 'editaccount'])->name('editaccount');

/* Class info */
Route::get('classes', [App\Http\Controllers\HomeController::class, 'classmanagement'])->name('classmanagement');
Route::get('classes/wait', [App\Http\Controllers\HomeController::class, 'classmanagement'])->name('classmanagement.waiting');
Route::get('class/new', [App\Http\Controllers\HomeController::class, 'class'])->name('newclass');
Route::get('class/edit/{id}', [App\Http\Controllers\HomeController::class, 'class'])->name('editclass');
Route::delete('class/delete/{id}', [App\Http\Controllers\HomeController::class, 'deleteclass'])->name('deleteclass');
Route::post('saveclass', [App\Http\Controllers\HomeController::class, 'saveclass'])->name('saveclass');
Route::post('class/checkname', [App\Http\Controllers\HomeController::class, 'checkExistsClassName'])->name('ajax.checkname');

/* SHIFT info */
Route::get('shift', [App\Http\Controllers\HomeController::class, 'shiftmanagement'])->name('shiftmanagement');
Route::get('shift/new', [App\Http\Controllers\HomeController::class, 'shift'])->name('newshift');
Route::get('shift/edit/{id}', [App\Http\Controllers\HomeController::class, 'shift'])->name('editshift');
Route::delete('shift/delete/{id}', [App\Http\Controllers\HomeController::class, 'deleteshift'])->name('deleteshift');
Route::post('saveshift', [App\Http\Controllers\HomeController::class, 'saveclass'])->name('saveclass');
Route::post('shift/checkname', [App\Http\Controllers\HomeController::class, 'checkExistsShiftName'])->name('ajax.checkname');

Route::any('{url}', static function() {
    return view('errors.404')->with(['uinfo' => auth()->user(), 'activeSidebar' => 'null']);
})->where('url', '.*');
