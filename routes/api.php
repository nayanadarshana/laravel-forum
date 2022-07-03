<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('users/register', [UserController::class, 'store']);

Route::post('users/authenticate', [UserController::class, 'login']);

Route::group(["middleware" => 'auth:api'], function () {
    Route::get('users/userProfile', [UserController::class, 'validateUser']);
    Route::post('users/logout', [UserController::class, 'logout']);

    Route::post('posts', [PostController::class, 'store']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
    Route::post('posts/approval/{id}/', [PostController::class, 'approvePost']);
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'edit']);
    Route::post('posts/{postId}/comments', [PostController::class, 'comment']);
    Route::get('posts/{postId}/comments', [PostController::class, 'getComments']);
});
