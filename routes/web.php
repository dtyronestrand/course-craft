<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\GoogleAuthController;
Route::get('/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

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
Route::get('courses/{course}/map', [\App\Http\Controllers\CourseController::class, 'map'])->name('courses.map');
Route::get('courses/{course}/storyboard', [\App\Http\Controllers\CourseController::class, 'storyboard'])->name('courses.storyboard');
Route::post('/courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
Route::post('/course_modules', [\App\Http\Controllers\CourseModuleController::class, 'store'])->name('course_modules.store');
Route::put('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'update'])->name('course_modules.update');
Route::delete('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'destroy'])->name('course_modules.destroy');
Route::post('/module_overviews', [\App\Http\Controllers\ModuleOverviewController::class, 'store'])->name('module.overview.store');
Route::put('/module_overviews/{moduleOverview}', [\App\Http\Controllers\ModuleOverviewController::class, 'update'])->name('module.overview.update');
Route::post('/course_pages', [\App\Http\Controllers\CoursePageController::class, 'store'])->name('course.page.store');
Route::put('/course_pages/{coursePage}', [\App\Http\Controllers\CoursePageController::class, 'update'])->name('course.page.update');
Route::delete('/course_pages/{coursePage}', [\App\Http\Controllers\CoursePageController::class, 'destroy'])->name('course.page.destroy');
Route::post('/course_assignments', [\App\Http\Controllers\CourseAssignmentController::class, 'store'])->name('course.assignment.store');
Route::put('/course_assignments/{courseAssignment}', [\App\Http\Controllers\CourseAssignmentController::class, 'update'])->name('course.assignment.update');
Route::delete('/course_assignments/{courseAssignment}', [\App\Http\Controllers\CourseAssignmentController::class, 'destroy'])->name('course.assignment.destroy');
Route::post('/course_discussions', [\App\Http\Controllers\CourseDiscussionController::class, 'store'])->name('course.discussion.store');
Route::put('/course_discussions/{courseDiscussion}', [\App\Http\Controllers\CourseDiscussionController::class, 'update'])->name('course.discussion.update');
Route::delete('/course_discussions/{courseDiscussion}', [\App\Http\Controllers\CourseDiscussionController::class, 'destroy'])->name('course.discussion.destroy');
Route::post('/course_quizzes', [\App\Http\Controllers\CourseQuizController::class, 'store'])->name('course.quiz.store');
Route::put('/course_quizzes/{courseQuiz}', [\App\Http\Controllers\CourseQuizController::class, 'update'])->name('course.quiz.update');
Route::delete('/course_quizzes/{courseQuiz}', [\App\Http\Controllers\CourseQuizController::class, 'destroy'])->name('course.quiz.destroy');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
