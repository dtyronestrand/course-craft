<?php

namespace App\Services;

use App\Actions\CourseQuizzes\CreateCourseQuizAction;
use App\Actions\CourseQuizzes\UpdateCourseQuizAction;
use App\Actions\CourseQuizzes\DeleteCourseQuizAction;
use App\Models\CourseQuiz;
use App\Repositories\CourseQuizRepository;

class CourseQuizService
{
    protected $courseQuizRepository;

    public function __construct(CourseQuizRepository $courseQuizRepository)
    {
        $this->courseQuizRepository = $courseQuizRepository;
    }

    public function createQuiz(array $data)
    {
        return (new CreateCourseQuizAction($this->courseQuizRepository))->execute($data);
    }

    public function updateQuiz(CourseQuiz $courseQuiz, array $data)
    {
        return (new UpdateCourseQuizAction($this->courseQuizRepository))->execute($courseQuiz, $data);
    }

    public function deleteQuiz(CourseQuiz $courseQuiz)
    {
        return (new DeleteCourseQuizAction($this->courseQuizRepository))->execute($courseQuiz);
    }
}
