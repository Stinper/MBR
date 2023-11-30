<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('users/login', [AuthController::class, 'login']);
Route::post('users/register', [AuthController::class, 'register']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [CategoryController::class, 'list']);
    Route::get('/categories/{category}', [CategoryController::class, 'retrieve']);
    Route::post('/categories/', [CategoryController::class, 'create']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/sections', [SectionController::class, 'list']);
    Route::get('/sections/{section}', [SectionController::class, 'retrieve']);
    Route::post('/sections', [SectionController::class, 'create']);
    Route::put('/sections/{section}', [SectionController::class, 'update']);
    Route::delete('/sections/{section}', [SectionController::class, 'destroy']);

    Route::get('/topics', [TopicController::class, 'list']);
    Route::get('/topics/{topic}', [TopicController::class, 'retrieve']);
    Route::post('/topics', [TopicController::class, 'create']);
    Route::put('/topics/{topic}', [TopicController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy']);
    Route::post('/topics/{topic}/close', [TopicController::class, 'close']);
    Route::post('/topics/{topic}/open', [TopicController::class, 'open']);

    Route::get('/comments', [CommentController::class, 'list']);
    Route::get('/comments/{comment}', [CommentController::class, 'retrieve']);
    Route::post('/comments', [CommentController::class, 'create']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});




