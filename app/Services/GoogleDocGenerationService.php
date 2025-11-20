<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class GoogleDocGenerationService
{
    protected $courseRepository;
    protected $userRepository;
    protected $googleDocsService;

    public function __construct(CourseRepository $courseRepository, UserRepository $userRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
    }

    public function generate(int $courseId)
    {
        $user = Auth::user();

        if (!$user->hasGoogleAccess()) {
            return ['error' => 'Please connect your Google account first.'];
        }

        $course = $this->courseRepository->getById(Course::find($courseId), ['modules.courseObjectives', 'modules.assessments', 'modules.instructions', 'modules.materials', 'modules.needs']);

        $data = [
            'title' => $course->prefix . ' ' . $course->number . ': ' . $course->title,
            'modules' => $course->modules,
        ];

        $this->googleDocsService = new GoogleDocsService($user->google_token, $user->google_refresh_token);
        $result = $this->googleDocsService->createDocument("Generated Document - " . now()->format('Y-m-d H:i:s'), $data);

        $newToken = $this->googleDocsService->getAccessToken();
        if ($newToken !== $user->google_token) {
            $this->userRepository->update($user, ['google_token' => $newToken]);
        }

        return ['success' => 'Google Doc created successfully: ' . $result['url']];
    }
}
