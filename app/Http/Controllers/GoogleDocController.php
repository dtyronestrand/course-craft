<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDocsService;
use App\Models\Course;

class GoogleDocController extends Controller
{

  public function generate(Request $request)
  {
  $user = $request->user();
  
  if (!$user->hasGoogleAccess()) {
    return back()->with('error', 'Please connect your Google account first.');
  }

  $course = Course::find($request->course_id);

  $data =[
    'title' => $course->prefix.' '.$course->number.': '.$course->title,
    'modules' => $course->modules()->with(['courseObjectives', 'assessments', 'instructions', 'materials', 'needs'])->get(),
  ];

  $googleDocsService = new GoogleDocsService($user->google_token, $user->google_refresh_token);
  $result = $googleDocsService->createDocument("Generated Document - " . now()->format('Y-m-d H:i:s'), $data);

  $newToken = $googleDocsService->getAccessToken();
  if ($newToken !== $user->google_token) {
    $user->update(['google_token' => $newToken]);
  }

  return back()->with('success', 'Google Doc created successfully: ' . $result['url']);
  }
}