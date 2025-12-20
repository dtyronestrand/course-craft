<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\NotificationRepository;

class NotificationService
{
    public function __construct(
        private NotificationRepository $notificationRepository
    ) {}

    public function getUserNotifications(User $user, int $limit = 50)
    {
        return $this->notificationRepository->getUserNotifications($user, $limit);
    }

    public function markAsRead(User $user, string $notificationId)
    {
        return $this->notificationRepository->markNotificationAsRead($user, $notificationId);
    }

    public function markAllAsRead(User $user)
    {
        $this->notificationRepository->markAllNotificationsAsRead($user);
    }
}