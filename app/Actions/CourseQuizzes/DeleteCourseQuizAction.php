<?php

namespace App\Actions\CourseQuizzes;

use App\Models\CourseQuiz;
use App\Repositories\CourseQuizRepository;

class DeleteCourseQuizAction
{
    protected $courseQuizRepository;

    public function __construct(CourseQuizRepository $courseQuizRepository)
    {
        $this->courseQuizRepository = $courseQuizRepository;
    }

    public function execute(CourseQuiz $courseQuiz)
    {
        return $this->courseQuizRepository->delete($courseQuiz);
    }
}
