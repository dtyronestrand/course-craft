<?php

namespace App\Services;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersWorkloads()
    {
        return $this->userRepository->workload();
    }

    public function getAllUsersForCalendar()
    {
        return $this->userRepository->getAllForCalendar();
    }

    public function getUserProfile($user)
    {
        return $this->userRepository->getUserProfile($user);
    }

    public function searchUsers(string $query, User $currentUser, int $limit =10)
    {
        return $this->userRepository->searchUsers($query, $currentUser->id, $limit);
    }
}