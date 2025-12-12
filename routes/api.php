<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\DevelopmentCycleController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', [AdminController::class, 'userWorkloads']);

Route::middleware('auth:sanctum')->get('/capacity', [AdminSettingsController::class, 'getCapacity']);

Route::middleware('auth:sanctum')->get('/allUsers', [AdminController::class, 'allUsers']);

Route::middleware('auth:sanctum')->get('/development-cycles', [DevelopmentCycleController::class, 'index']);