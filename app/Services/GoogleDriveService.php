<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive;
use Google\Service\Docs;
use Illuminate\Support\Facades\Log; // Use Illuminate\Support\Facades\Log
use Illuminate\Support\Facades\Auth;

class GoogleDriveService
{
    protected $client;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $user = Auth::user();
        if (!$user || !$user->google_token) {
            throw new \Exception('User is not authenticated with Google.');
        }

        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        // Set the user's tokens
        $this->client->setAccessToken([
            'access_token' => $user->google_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in' => 3599 // A typical expiry time, though we'll refresh anyway
        ]);

        // Refresh the token if it's expired
        if ($this->client->isAccessTokenExpired()) {
            $this->refreshUserToken($user);
        }
    }

    /**
     * Get the Google_Client instance.
     */
    public function getClient(): GoogleClient
    {
        return $this->client;
    }

    /**
     * Get the Google_Service_Drive instance.
     */
    public function getDriveService(): Drive
    {
        return new Drive($this->client);
    }
    public function getDocsService(): Docs
    {
        return new Docs($this->client);
    }
    /**
     * Refresh the user's access token.
     */
    protected function refreshUserToken($user)
    {
        try {
            // Get the refresh token from the user
            $refreshToken = $user->google_refresh_token;
            if (!$refreshToken) {
                throw new \Exception('No refresh token found for user.');
            }
            
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newAccessToken = $this->client->getAccessToken();

            // Persist the new token to the database
            $user->update([
                'google_token' => $newAccessToken['access_token'],
                // Google sometimes issues a new refresh token, but not always
                'google_refresh_token' => $newAccessToken['refresh_token'] ?? $user->google_refresh_token
            ]);
            
            // Set the new token on the client for the current request
            $this->client->setAccessToken($newAccessToken);

        } catch (\Exception $e) {
            Log::error('Failed to refresh Google token: ' . $e->getMessage());
            // This will force the user to re-authenticate
            $user->update([
                'google_token' => null,
                'google_refresh_token' => null,
            ]);
            // Re-throw the exception to stop the current operation
            throw new \Exception('Google token expired and could not be refreshed. Please reconnect your account.');
        }
    }
}