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
        return User::select('first_name', 'last_name')->withCount('courses')->get();
    }

    public function getAllForCalendar()
    {
        return User::orderBy('last_name')->get(['id', 'first_name', 'last_name']);
    }
}
