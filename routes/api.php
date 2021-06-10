<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\JwtAuthController::class, 'login']);
Route::post('register', [\App\Http\Controllers\JwtAuthController::class, 'register']);

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', [\App\Http\Controllers\JwtAuthController::class, 'logout']);
    Route::get('user-info', [\App\Http\Controllers\JwtAuthController::class, 'getUser']);
    Route::post('post/create', [\App\Http\Controllers\Api\PostController::class, 'store']);
    Route::get('posts', [\App\Http\Controllers\Api\PostController::class, 'index']);
    Route::post('comment/create', [\App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::get('comments/{post_id}', [\App\Http\Controllers\Api\CommentController::class, 'index']);

});