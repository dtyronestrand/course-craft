<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    public function createProfile(array $data): Profile
    {
        return Profile::create($data);
    }

    public function updateProfile(Profile $profile, array $data)
    {
        return $profile->update($data);
    

    }

    public function getProfileByUserId(int $userId): ?Profile
    {
        return Profile::where('user_id', $userId)->first();
    }

    public function deleteProfile($profile)
    {
        return $profile->delete();
    }
}