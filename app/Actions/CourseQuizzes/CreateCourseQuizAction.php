<?php

namespace App\Actions\CourseQuizzes;

use App\Repositories\CourseQuizRepository;

class CreateCourseQuizAction
{
    protected $courseQuizRepository;

    public function __construct(CourseQuizRepository $courseQuizRepository)
    {
        $this->courseQuizRepository = $courseQuizRepository;
    }

    public function execute(array $data)
    {
        $courseQuiz = $this->courseQuizRepository->create([
            'title' => $data['title'],
            'instructions' => $data['instructions'],
            'settings' => $data['settings'],
            'questions' => $data['questions'],
        ]);

        $maxOrderIndex = $this->courseQuizRepository->getMaxOrderIndex($data['module']);

        $this->courseQuizRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $courseQuiz->id,
            'itemable_type' => 'quiz',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $courseQuiz;
    }
}
