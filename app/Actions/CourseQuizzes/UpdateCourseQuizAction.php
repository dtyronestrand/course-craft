<?php

namespace App\Actions\CourseQuizzes;

use App\Models\CourseQuiz;
use App\Repositories\CourseQuizRepository;

class UpdateCourseQuizAction
{
    protected $courseQuizRepository;

    public function __construct(CourseQuizRepository $courseQuizRepository)
    {
        $this->courseQuizRepository = $courseQuizRepository;
    }

    public function execute(CourseQuiz $courseQuiz, array $data)
    {
        return $this->courseQuizRepository->update($courseQuiz, [
            'title' => $data['title'],
            'instructions' => $data['instructions'],
            'settings' => $data['settings'],
            'questions' => $data['questions'],
        ]);
    }
}
