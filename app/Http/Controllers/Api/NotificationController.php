<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $notifications = $this->notificationService->getUserNotifications($request->user());
        
        return response()->json($notifications);
    }

    public function markAsRead(Request $request, string $notificationId)
    {
        $this->notificationService->markAsRead($request->user(), $notificationId);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $this->notificationService->markAllAsRead($request->user());
        
        return response()->json(['success' => true]);
    }
}