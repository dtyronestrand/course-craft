<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\DevelopmentCycleController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', [AdminController::class, 'userWorkloads']);

Route::middleware('auth:sanctum')->get('/capacity', [AdminSettingsController::class, 'getCapacity']);

Route::middleware('auth:sanctum')->get('/allUsers', [AdminController::class, 'allUsers']);

Route::middleware('auth:sanctum')->get('/development-cycles', [DevelopmentCycleController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    // Existing routes...
    
    Route::get('/api/users/search', function (Request $request) {
        $query = $request->input('q');
        
        return User::where('id', '!=', $request->user()->id)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email']);
    });
    
    Route::get('/api/courses', function (Request $request) {
        // Adjust this based on your project authorization logic
        return $request->user()
            ->courses()
            ->get(['id', 'prefix','number']);
    });
});