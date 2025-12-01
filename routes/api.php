<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', [AdminController::class, 'userWorkloads']);

Route::middleware('auth:sanctum')->get('/capacity', [AdminSettingsController::class, 'getCapacity']);
