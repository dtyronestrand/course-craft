<?php

namespace App\Actions\CourseModules;

use App\Models\CourseModule;
use App\Repositories\CourseModuleRepository;

class DeleteCourseModuleAction
{
    protected $courseModuleRepository;

    public function __construct(CourseModuleRepository $courseModuleRepository)
    {
        $this->courseModuleRepository = $courseModuleRepository;
    }

    public function execute(CourseModule $courseModule)
    {
        return $this->courseModuleRepository->deleteModule($courseModule);
    }
}
