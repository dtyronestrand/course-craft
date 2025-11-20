<?php

namespace App\Services;

use App\Actions\CourseModules\CreateCourseModuleAction;
use App\Actions\CourseModules\UpdateCourseModuleAction;
use App\Actions\CourseModules\DeleteCourseModuleAction;
use App\Models\CourseModule;
use App\Repositories\CourseModuleRepository;

class CourseModuleService
{
    protected $courseModuleRepository;

    public function __construct(CourseModuleRepository $courseModuleRepository)
    {
        $this->courseModuleRepository = $courseModuleRepository;
    }

    public function createModule(array $data)
    {
        return (new CreateCourseModuleAction($this->courseModuleRepository))->execute($data);
    }

    public function updateModule(CourseModule $courseModule, array $data)
    {
        return (new UpdateCourseModuleAction($this->courseModuleRepository))->execute($courseModule, $data);
    }

    public function deleteModule(CourseModule $courseModule)
    {
        return (new DeleteCourseModuleAction($this->courseModuleRepository))->execute($courseModule);
    }
}
