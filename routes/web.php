<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleDocController;
use Illuminate\Support\Facades\Auth;


Route::get('/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


Route::get('/dashboard', [\App\Http\Controllers\CourseController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('/auth/google/disconnect', [GoogleAuthController::class, 'disconnectGoogle'])->name('google.disconnect');
    Route::get('/auth/google/status', [GoogleAuthController::class, 'checkGoogleStatus'])->name('google.status');
    Route::post('/export/google-doc', [GoogleDocController::class, 'generate'])->name('google.doc.generate');
});

Route::get('/courses/create', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    $cycles = \App\Models\DevelopmentCycle::select('id', 'name')->get();
    
    if (request()->wantsJson()) {
        return response()->json(['users' => $users, 'cycles' => $cycles]);
    }
    
    return inertia('Dashboard', [
        'users' => $users,
        'cycles' => $cycles
    ]);
})->name('courses.create');

Route::get('courses/{course}/delete', [\App\Http\Controllers\CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');
Route::get('courses/{course}/map', [\App\Http\Controllers\CourseController::class, 'map'])->name('courses.map');
Route::get('courses/{course}/storyboard', [\App\Http\Controllers\CourseController::class, 'storyboard'])->name('courses.storyboard');
Route::post('/courses', [\App\Http\Controllers\CourseController::class, 'store'])->name('courses.store');
Route::post('/courses/{course}/update', [\App\Http\Controllers\CourseController::class, 'update'])->name('courses.update');

Route::post('/course_modules', [\App\Http\Controllers\CourseModuleController::class, 'store'])->name('course_modules.store');
Route::put('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'update'])->name('course_modules.update');
Route::delete('/course_modules/{courseModule}', [\App\Http\Controllers\CourseModuleController::class, 'destroy'])->name('course_modules.destroy');
Route::post('/module_overviews', [\App\Http\Controllers\ModuleOverviewController::class, 'store'])->name('module.overview.store');
Route::put('/module_overviews/{moduleOverview}', [\App\Http\Controllers\ModuleOverviewController::class, 'update'])->name('module.overview.update');
Route::delete('/module_overviews/{moduleOverview}', [\App\Http\Controllers\ModuleOverviewController::class, 'destroy'])->name('module.overview.destroy');

Route::post('/course_pages', [\App\Http\Controllers\CoursePageController::class, 'store'])->name('course.page.store');
Route::put('/course_pages/{coursePage}', [\App\Http\Controllers\CoursePageController::class, 'update'])->name('course.page.update');
Route::delete('/course_pages/{coursePage}', [\App\Http\Controllers\CoursePageController::class, 'destroy'])->name('course.page.destroy');

Route::post('/course_assignments', [\App\Http\Controllers\CourseAssignmentController::class, 'store'])->name('course.assignment.store');
Route::put('/course_assignments/{courseAssignment}', [\App\Http\Controllers\CourseAssignmentController::class, 'update'])->name('course.assignment.update');
Route::delete('/course_assignments/{courseAssignment}', [\App\Http\Controllers\CourseAssignmentController::class, 'destroy'])->name('course.assignment.destroy');

Route::post('/course_discussions', [\App\Http\Controllers\CourseDiscussionController::class, 'store'])->name('course.discussion.store');
Route::put('/course_discussions/{courseDiscussion}', [\App\Http\Controllers\CourseDiscussionController::class, 'update'])->name('course.discussion.update');
Route::delete('/course_discussions/{courseDiscussion}', [\App\Http\Controllers\CourseDiscussionController::class, 'destroy'])->name('course.discussion.destroy');
Route::put('/courses/{course}/deliverables/{deliverable}', [\App\Http\Controllers\CourseController::class, 'updateDeliverable'])->name('course.deliverable.update');
Route::post('/course_quizzes', [\App\Http\Controllers\CourseQuizController::class, 'store'])->name('course.quiz.store');
Route::put('/course_quizzes/{courseQuiz}', [\App\Http\Controllers\CourseQuizController::class, 'update'])->name('course.quiz.update');
Route::delete('/course_quizzes/{courseQuiz}', [\App\Http\Controllers\CourseQuizController::class, 'destroy'])->name('course.quiz.destroy');

Route::post('/module_wrapUps', [\App\Http\Controllers\ModuleWrapUpController::class, 'store'])->name('module.wrapup.store');
Route::put('/module_wrapUps/{moduleWrapUp}', [\App\Http\Controllers\ModuleWrapUpController::class, 'update'])->name('module.wrapup.update');
Route::delete('/module_wrapUps/{moduleWrapUp}', [\App\Http\Controllers\ModuleWrapUpController::class, 'destroy'])->name('module.wrapup.destroy');
Route::middleware([\App\Http\Middleware\isAdminMiddleWare::class])->group(function () {
 
});
Route::prefix('admin')->middleware([\App\Http\Middleware\isAdminMiddleWare::class])->group(function() {
   Route::get('/courses', [\App\Http\Controllers\AdminController::class, 'courses'])->name('admin.courses');
    Route::get('/courses/{course}', [\App\Http\Controllers\AdminController::class, 'courseDetails'])->name('admin.course.details');
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'store'])->name('admin.settings.store');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::post('/settings', [\App\Http\Controllers\AdminSettingsController::class, 'store'])->name('admin.settings.store');
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('admin.calendar');
Route::post('/deliverables', [\App\Http\Controllers\DeliverableController::class, 'store'])->name('admin.deliverables.store');
Route::post('/deliverables/{deliverable}', [\App\Http\Controllers\DeliverableController::class, 'update'])->name('admin.deliverables.update');
Route::delete('/deliverables/{deliverable}', [\App\Http\Controllers\DeliverableController::class, 'destroy'])->name('admin.deliverables.destroy');
Route::get('/course-details/{course}', [\App\Http\Controllers\AdminController::class, 'courseDetails'])->name('admin.course.details');
});

Route::post('/appointments', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
Route::post('/appointments/{appointment}', [\App\Http\Controllers\AppointmentController::class, 'update'])->name('appointments.update');
Route::get('conversations/{conversation}', [\App\Http\Controllers\ConversationsController::class, 'show'])->name('conversations.show');
Route::get('profiles/{user}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profiles.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/chat.php';
