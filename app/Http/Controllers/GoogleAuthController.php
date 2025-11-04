<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
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
        $user = Auth::user();
        
        // Optionally revoke the token with Google
        if ($user->google_token) {
            try {
                $client = new \Google\Client();
                $client->revokeToken($user->google_token);
            } catch (\Exception $e) {
                Log::warning('Failed to revoke Google token: ' . $e->getMessage());
            }
        }
        
        // Clear tokens from database
        $user->update([
            'google_id' => null,
            'google_token' => null,
            'google_refresh_token' => null,
            'google_token_expires_at' => null
        ]);
        
        return back()->with('status', 'Google account disconnected.');
    }
 /**
 * Handle the incoming Google user.
 */
public function callback()
{
    try {
        // 1. Get the user details from Google
        $googleUser = Socialite::driver('google')->user();

        // 2. Get the *currently authenticated* user from your app
        /** @var User $user */
        $user = Auth::user();

        // 3. If no one is logged in, send them to the login page
        if (!$user) {
            return redirect('/login')->withErrors('You must be logged in to connect Google Drive.');
        }

        // 4. Save the tokens to the currently logged-in user
        $user->google_id = $googleUser->getId();
        $user->google_token = $googleUser->token;

        // 5. IMPORTANT: Only update the refresh token if Google provides one
        //    (It only sends one on the *first* authorization)
        if ($googleUser->refreshToken) {
            $user->google_refresh_token = $googleUser->refreshToken;
        }
     if (property_exists($googleUser, 'expiresIn') && $googleUser->expiresIn) {
            $user->google_token_expires_at = now()->addSeconds($googleUser->expiresIn);
        } else {
            // Default to 1 hour if not provided
            $user->google_token_expires_at = now()->addHour();
        }

        $user->save();

        $url = session()->pull('google_auth_intended_uri', '/dashboard');
        // 6. Redirect them back to their settings page with a success message
        //    (Change '/settings' to your desired page)
        return redirect($url)->with('status', 'Google Drive connected successfully!');

    } catch (\Exception $e) {
        report($e);
        // Redirect back to settings with an error
        $url = session()->pull('google_auth_intended_uri', '/dashboard');
        return redirect($url)->withErrors('Failed to connect Google Drive. Please try again.');
    }
}
}