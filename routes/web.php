<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;

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

//for views
Route::get('login', function () {
    return view('login');
});
Route::get('register', function () { return view('register');
});

Route::get('group', function () { return view('myviews.create_group');
});
Route::get('keyword', function () { return view('myviews.create_keywords');
});



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/verify-mail/{token}', [])

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('forgetpas', [UserController::class, 'forgetpassword']);
Route::post('chapass', [UserController::class, 'changepass']);
Route::post('reg', [UserController::class, 'register']);
Route::post('log', [UserController::class, 'login']);
// Route::any('upd_pass/{id}', [UserController::class, 'updpass']);
Route::get('/reset-password', [UserController::class, 'resetpass']);
Route::post('/reset-password', [UserController::class, 'updpass']);


Route::post('create_group', [GroupController::class, "index"]);
Route::get('viewprofile', [GroupController::class, "mypro"]);
Route::post('create_keywords', [GroupController::class, "keywords"]);
Route::get('list_group', [GroupController::class, "listgro"]);
Route::post('list_keyword', [GroupController::class, "listkey"]);
