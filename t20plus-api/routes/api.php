<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\ArmorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GodController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/dev-login', [AuthController::class, 'devLogin']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('characters', CharacterController::class)->only(['index']);
    Route::apiResource('campaigns', CampaignController::class)->only(['index']);
    Route::apiResource('races', RaceController::class)->only(['index']);
    Route::apiResource('origins', OriginController::class)->only(['index']);
    Route::apiResource('gods', GodController::class)->only(['index']);
    Route::apiResource('classes', ClassController::class)->only(['index']);
    Route::apiResource('skills', SkillController::class)->only(['index']);
    Route::apiResource('powers', PowerController::class)->only(['index']);
    Route::apiResource('accessories', AccessoryController::class)->only(['index']);
    Route::apiResource('armors', ArmorController::class)->only(['index']);
});
