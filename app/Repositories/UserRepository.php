<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function update(User $user, array $data)
    {
        return $user->update($data);
    }
}
