<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\CourseController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/courses/create', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    
    if (request()->wantsJson()) {
        return response()->json(['users' => $users]);
    }
    
    return inertia('Dashboard', [
        'users' => $users
    ]);
})->name('courses.create');

Route::get('courses/{course}/delete', [\App\Http\Controllers\CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');
Route::post('/courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
Route::post('/course_modules', [\App\Http\Controllers\CourseModuleController::class, 'store'])->name('course_modules.store');
Route::put('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'update'])->name('course_modules.update');
Route::delete('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'destroy'])->name('course_modules.destroy');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
