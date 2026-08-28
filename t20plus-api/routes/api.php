<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\GodController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\RaceController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/dev-login', [AuthController::class, 'devLogin']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('characters', CharacterController::class)->only(['index']);
    Route::apiResource('campaigns', CampaignController::class)->only(['index']);
    Route::apiResource('races', RaceController::class)->only(['index']);
    Route::apiResource('origins', OriginController::class)->only(['index']);
    Route::apiResource('gods', GodController::class)->only(['index']);
});
