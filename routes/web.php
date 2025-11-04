<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleDocController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

Route::get('/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\CourseController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('/auth/google/disconnect', [GoogleAuthController::class, 'disconnectGoogle'])->name('google.disconnect');
    Route::get('/auth/google/status', [GoogleAuthController::class, 'checkGoogleStatus'])->name('google.status');
    Route::post('/export/google-doc', [GoogleDocController::class, 'generate'])->name('google.doc.generate');
});

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


   
    if (isset($debugInfo['authorized_scopes'])) {
        $missingScopes = array_diff($requiredScopes, $debugInfo['authorized_scopes']);
        $debugInfo['missing_scopes'] = array_values($missingScopes);
        $debugInfo['has_all_required_scopes'] = empty($missingScopes);
    }
    
Route::get('/test-google-apis', function() {
    $user = Auth::user();
    
    if (!$user->google_token) {
        return 'Not connected';
    }
    
    $results = [];
    
    try {
        $client = new \Google\Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($user->google_token);
        
        if ($client->isAccessTokenExpired() && $user->google_refresh_token) {
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $newToken = $client->getAccessToken();
            $user->update([
                'google_token' => $newToken['access_token'],
                'google_refresh_token' => $newToken['refresh_token'] ?? $user->google_refresh_token,
            ]);
            $client->setAccessToken($newToken);
        }
        
        // Test 1: Drive API - List files
        try {
            $driveService = new \Google\Service\Drive($client);
            $files = $driveService->files->listFiles(['pageSize' => 1]);
            $results['drive_list_files'] = 'SUCCESS - Can list files';
        } catch (\Exception $e) {
            $results['drive_list_files'] = 'FAILED: ' . $e->getMessage();
        }
        
        // Test 2: Drive API - Get About info
        try {
            $driveService = new \Google\Service\Drive($client);
            $about = $driveService->about->get(['fields' => 'user']);
            $results['drive_about'] = 'SUCCESS - User: ' . $about->getUser()->getEmailAddress();
        } catch (\Exception $e) {
            $results['drive_about'] = 'FAILED: ' . $e->getMessage();
        }
        
        // Test 3: Drive API - Create a file
        try {
            $driveService = new \Google\Service\Drive($client);
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => 'Test File ' . now(),
                'mimeType' => 'application/vnd.google-apps.document'
            ]);
            $file = $driveService->files->create($fileMetadata);
            $results['drive_create_file'] = 'SUCCESS - Created file: ' . $file->getId();
            
            // Clean up
            try {
                $driveService->files->delete($file->getId());
                $results['drive_cleanup'] = 'Deleted test file';
            } catch (\Exception $e) {
                $results['drive_cleanup'] = 'Could not delete: ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $results['drive_create_file'] = 'FAILED: ' . $e->getMessage();
        }
        
        // Test 4: Docs API - Create document
        try {
            $docsService = new \Google\Service\Docs($client);
            $document = new \Google\Service\Docs\Document([
                'title' => 'Test Doc ' . now()
            ]);
            $createdDoc = $docsService->documents->create($document);
            $results['docs_create'] = 'SUCCESS - Created doc: ' . $createdDoc->getDocumentId();
            
            // Clean up
            try {
                $driveService = new \Google\Service\Drive($client);
                $driveService->files->delete($createdDoc->getDocumentId());
                $results['docs_cleanup'] = 'Deleted test doc';
            } catch (\Exception $e) {
                $results['docs_cleanup'] = 'Could not delete: ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $results['docs_create'] = 'FAILED: ' . $e->getMessage();
        }
        
        // Test 5: Check actual token scopes from Google
        try {
            $http = new \GuzzleHttp\Client();
            $response = $http->get('https://oauth2.googleapis.com/tokeninfo', [
                'query' => ['access_token' => $user->google_token]
            ]);
            $tokenInfo = json_decode($response->getBody(), true);
            $results['actual_scopes'] = explode(' ', $tokenInfo['scope'] ?? '');
        } catch (\Exception $e) {
            $results['actual_scopes'] = 'Could not fetch: ' . $e->getMessage();
        }
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'results_so_far' => $results
        ], 500);
    }
})->middleware('auth');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
