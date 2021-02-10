<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TagController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/articles', [ArticleController::class, 'getAll']);
Route::get('/articles/{article}/comments', [ArticleController::class, 'getArticleComments']);

Route::get('/tags', [TagController::class, 'getAll']);
Route::get('/tags/{id}/articles', [TagController::class, 'getTagArticles']);

Route::fallback(function(){
    $msg = ['message' => 'Page Not Found.'];
    return response($msg, 404)->header('Content-Type', 'application/json');
});