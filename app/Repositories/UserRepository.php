<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function workload()
    {
        return User::select('id', 'first_name', 'last_name')->with('courses')->with('profile')->withCount('courses')->get();
    }

    public function getAllForCalendar()
    {
        return User::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
    }

    public function getUserProfile(User $user)
    {
        return $user->load('profile');
    }

    public function searchUsers(string $query, int $excludeUserId, int $limit =10)
    {
        return User::where('id', '!=', $excludeUserId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get(['id', 'first_name', 'last_name']);
    }
}
