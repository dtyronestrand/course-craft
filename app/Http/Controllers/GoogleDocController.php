<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDriveService;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // <-- 1. ADD THIS
use Google\Service\Docs; // <-- 2. ADD THIS
use Google\Service\Docs\BatchUpdateDocumentRequest;
use Goolgle\Service\Docs\InertTextRequest;
use Google\Service\Docs\Request as DocsRequest;
use App\Models\Course;

class GoogleDocController extends Controller
{
    public function createDoc(Request $request, GoogleDriveService $driveService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $drive = $driveService->getDriveService();
            $doc = $driveService->getDocsService();

            $file = new DriveFile([
                'name' => $request->input('title'),
                'mimeType' => 'application/vnd.google-apps.document',
            ]);

            $createdFile = $drive->files->create($file, [
                'fields' => 'id, webViewLink'
            ]);

            $documentId = $createdFile->id;

            $requests = [
                new DocsRequest([
                    'insertText'=>[
                        'location' => ['index' => 1],
                        'text' => $request->input('content'),
                    ]
                ])
                    ];

                    $batchUpdatedRequest = new BatchUpdateDocumentRequest([
                        'requests' => $requests
                    ]);

            $doc->documents->batchUpdate($documentId, $batchUpdatedRequest);
            
            // Save document ID to the course
            if ($request->has('course_id')) {
                $course = Course::find($request->input('course_id'));
                if ($course) {
                    $course->document_id = $documentId;
                    $course->save();
                }
            }
            return response()->json([
                'message'=>'File created successfully',
                'url' => $createdFile->webViewLink,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating file: ' . $e->getMessage());

            // --- THIS IS THE FINAL, ROBUST FIX ---

            // Check if the exception is a 401 error, OR
            // if it's our custom "could not be refreshed" exception.
            if ($e->getCode() == 401 || str_contains($e->getMessage(), 'could not be refreshed')) {
                
                // Get the currently logged-in user
                $user = Auth::user();
                
                // Delete their stale tokens from the database
                // (It's okay if the service already did this;
                //  we're just making sure it's done.)
                if ($user) {
                    $user->google_token = null;
                    $user->google_refresh_token = null;
                    $user->save();
                }

                // NOW return the 401 error
                return response()->json([
                    'error' => 'Google connection invalid. Please reconnect your account.',
                ], 401);
            }

            return response()->json([
                'message' => 'Error creating file',
            ], 500);
        }
    }

    public function updateGoogleDoc(Request $request, GoogleDriveService $driveService, $documentId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $doc = $driveService->getDocsService();

            $requests = [
                new DocsRequest([
                    'insertText'=>[
                        'location' => ['index' => 1],
                        'text' => $request->input('content'),
                    ]
                ])
            ];

            $batchUpdatedRequest = new BatchUpdateDocumentRequest([
                'requests' => $requests
            ]);

            $doc->documents->batchUpdate($documentId, $batchUpdatedRequest);

            return response()->json([
                'message'=>'Document updated successfully',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating document: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating document',
            ], 500);
        }
    }
}