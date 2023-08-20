<?php

use App\Http\Controllers\Api\ElasticsearchController;
use App\Http\Controllers\Api\OpenAIController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/embeddings')->controller(OpenAIController::class)->name('embeddings.')->group(function () {
    Route::post('/', 'createEmbedding')->name('create');
});

Route::prefix('/elasticsearch')->controller(ElasticsearchController::class)->name('elasticsearch.')->group(function () {
    Route::get('/recommendations', 'getRecommendations')->name('get');
});
