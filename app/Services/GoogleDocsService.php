<?php

namespace App\Services;

use Google\Client;
use Google\Service\Docs;
use Google\Service\Drive;
use Google\Service\Docs\Request;
use Google\Service\Docs\BatchUpdateDocumentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleDocsService
{
    private $docsService;
    private $driveService;
    private $client;

    public function __construct($accessToken = null, $refreshToken = null)
    {
        $user = Auth::user();
    
    if (!$user || !$user->google_token) {
        throw new \Exception('User is not authenticated with Google.');
    }

    $this->client = new Client();
    $this->client->setClientId(config('services.google.client_id'));
    $this->client->setClientSecret(config('services.google.client_secret'));
    
    // Set the user's tokens
    $this->client->setAccessToken([
        'access_token' => $user->google_token,
        'refresh_token' => $user->google_refresh_token,
        'expires_in' => 3599
    ]);

    // Refresh if expired
    if ($this->client->isAccessTokenExpired()) {
        $this->refreshUserToken($user);
    }
    
    $this->docsService = new Docs($this->client);
    $this->driveService = new Drive($this->client);
    }
    private function refreshUserToken($user)
    {
        try {
            $refreshToken = $user->google_refresh_token;
            if (!$refreshToken) {
                throw new \Exception('No refresh token found for user.');
            }
            
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newAccessToken = $this->client->getAccessToken();

            $updateData = [
                'google_token' => $newAccessToken['access_token'],
                'google_refresh_token' => $newAccessToken['refresh_token'] ?? $user->google_refresh_token
            ];

            if(isset($newAccessToken['expires_in'])) {
                $updateData['google_token_expires_at'] = now()->addSeconds($newAccessToken['expires_in']);
            }
            // Update user's tokens
            $user->update(
                $updateData
            );
            
            $this->client->setAccessToken($newAccessToken);

        } catch (\Exception $e) {
            Log::error('Failed to refresh Google token: ' . $e->getMessage());
            
            // Clear invalid tokens
            $user->update([
                'google_token' => null,
                'google_refresh_token' => null,
            ]);
            
            throw new \Exception('Google token expired. Please reconnect your Google account.');
        }
    }
    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }

    public function createDocument($title, $data)
    {
        // Create the Google Doc
        $document = new Docs\Document([
            'title' => $title,
        ]);

        $createdDocument = $this->docsService->documents->create($document);
        $documentId = $createdDocument->getDocumentId();
        // Insert content into the document
        $requests = $this->buildFormatRequests($data);

        if(!empty($requests)){
            $batchUpdateRequest = new BatchUpdateDocumentRequest([
                'requests' => $requests,
            ]);

            $this->docsService->documents->batchUpdate($documentId, $batchUpdateRequest);
        }

        return [
            'documentId' => $documentId,
            'url' => 'https://docs.google.com/document/d/' . $documentId . '/edit',
        ];
    }

    private function buildFormatRequests($data)
    {
        $requests = [];
        $index = 1;
        // Example: Insert text
        if (isset($data['title'])) {
            $requests[] = new Request([
                'insertText' => [
                    'location' => [
                        'index' => $index
                    ],
                    'text' => $data['title'] . "\n\n",
                ],
            ]);

            $requests[] = new Request([
                'updateTextStyle'=>[
                    'range'=>[
                        'startIndex'=> $index,
                        'endIndex'=> $index + strlen($data['title'])
                    ],
                    'textStyle'=>[
                        'bold'=> true,
                        'fontSize'=>[
                            'magnitude'=>24,
                            'unit'=>'PT'
                        ]
                    ],
                    'fields'=>'bold, fontSize'
                ]
            ]);
            $index += strlen($data['title']) + 2;
        }

        if(isset($data['content'])) {
            $requests[] = new Request([
                'insertText' => [
                    'location' => [
                        'index' => $index
                    ],
                    'text' => $data['content'] . "\n",
                ],
            ]);
            $index += strlen($data['content']) + 1;
        }

        // Add more formatting requests as needed

        return $requests;
    }


}