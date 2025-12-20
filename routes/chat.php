<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;

Route::middleware('auth')->group(function() {
    Route::get('/conversations', [ChatController::class, 'index'])->name('conversations.index');
    Route::post('/conversations', [ChatController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('conversations.show');

    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/conversations/{conversation}/typing', [MessageController::class, 'typing'])->name('messages.typing');
    Route::get('/notifications', function (Request $request) {
        return $request->user()
            ->notifications()
            ->latest()
            ->limit(50)
            ->get();
    });

    Route::post('/notifications/{notification}/read', function (Request $request, $notificationId) {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($notificationId);
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    });
    
    Route::post('/notifications/mark-all-read', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });
});