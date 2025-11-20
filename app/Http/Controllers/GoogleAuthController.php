<?php

namespace App\Http\Controllers;

use App\Services\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    protected $googleAuthService;

    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    public function redirect()
    {
        session(['google_auth_intended_uri' => url()->previous()]);
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        return $driver->scopes([
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/drive',
                'https://www.googleapis.com/auth/documents'
            ])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function disconnect()
    {
        $this->googleAuthService->disconnect();

        return back()->with('status', 'Google account disconnected.');
    }

    public function callback()
    {
        try {
            if (!$this->googleAuthService->handleCallback()) {
                return redirect('/login')->withErrors('You must be logged in to connect Google Drive.');
            }

            $url = session()->pull('google_auth_intended_uri', '/dashboard');
            return redirect($url)->with('status', 'Google Drive connected successfully!');
        } catch (\Exception $e) {
            report($e);
            $url = session()->pull('google_auth_intended_uri', '/dashboard');
            return redirect($url)->withErrors('Failed to connect Google Drive. Please try again.');
        }
    }
}
