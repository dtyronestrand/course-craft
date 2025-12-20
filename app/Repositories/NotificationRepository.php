<?php

namespace App\Repositories;

use App\Models\User;

class NotificationRepository
{
    public function getUserNotifications(User $user, int $limit = 50)
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markNotificationAsRead(User $user, string $notificationId)
    {
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        
        return $notification;
    }

    public function markAllNotificationsAsRead(User $user)
    {
        $user->unreadNotifications->markAsRead();
    }
}