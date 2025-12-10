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
        return User::select('id', 'first_name', 'last_name')->with('profile')->withCount('courses')->get();
    }

    public function getAllForCalendar()
    {
        return User::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
    }

    public function getUserProfile(User $user)
    {
        return $user->load('profile');
    }
}
