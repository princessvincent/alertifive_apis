<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function() {
    return response()->json(['status' => 'Welcome to Laravel']);
});


Route::post('forgetpas', [UserController::class, 'forgetpassword']);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('allusers', [UserController::class, 'allusers']);

Route::group(['middleware' => 'auth:sanctum'], function () {
Route::post('create_group', [GroupController::class, "index"]);
Route::get('view_profile', [GroupController::class, "profile"]);
Route::post('create_keywords', [GroupController::class, "keywords"]);
Route::get('listgro', [GroupController::class, "listgroup"]);
Route::get('list_keywords', [GroupController::class, "listkey"]);
Route::get('list_group_keywords/{id}', [GroupController::class, "listkey_group"]);
Route::post('changepassword', [UserController::class, 'changepass']);
Route::delete('deletegroup/{id}', [GroupController::class, 'deletegroup']);
Route::delete('deletekeyword/{id}', [GroupController::class, 'deletekeyword']);
Route::post('groupsetting/{id}', [GroupController::class, 'groupsetting']);
Route::post('inviteuser', [GroupController::class, 'inviteuser']);
Route::get('groupmembers/{id}', [GroupController::class, 'groupuser']);
Route::post('message', [GroupController::class, 'message']);
Route::get('viewmessage', [GroupController::class, 'viewmessage']);


});
