<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\CourseController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('courses/{course}/delete', [\App\Http\Controllers\CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');

Route::get('/courses/create', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    
    if (request()->wantsJson()) {
        return response()->json(['users' => $users]);
    }
    
    return inertia('Dashboard', [
        'users' => $users
    ]);
})->name('courses.create');
Route::post('/courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
