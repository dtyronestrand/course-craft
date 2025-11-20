<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $data = [
            'google_id' => $googleUser->getId(),
            'google_token' => $googleUser->token,
        ];

        if ($googleUser->refreshToken) {
            $data['google_refresh_token'] = $googleUser->refreshToken;
        }

        if (property_exists($googleUser, 'expiresIn') && $googleUser->expiresIn) {
            $data['google_token_expires_at'] = now()->addSeconds($googleUser->expiresIn);
        } else {
            $data['google_token_expires_at'] = now()->addHour();
        }

        $this->userRepository->update($user, $data);

        return true;
    }

    public function disconnect()
    {
        $user = Auth::user();

        if ($user->google_token) {
            try {
                $client = new \Google\Client();
                $client->revokeToken($user->google_token);
            } catch (\Exception $e) {
                Log::warning('Failed to revoke Google token: ' . $e->getMessage());
            }
        }

        $this->userRepository->update($user, [
            'google_id' => null,
            'google_token' => null,
            'google_refresh_token' => null,
            'google_token_expires_at' => null,
        ]);
    }
}
