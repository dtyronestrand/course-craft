<?php

namespace App\Actions\CourseModules;

use App\Repositories\CourseModuleRepository;

class CreateCourseModuleAction
{
    protected $courseModuleRepository;

    public function __construct(CourseModuleRepository $courseModuleRepository)
    {
        $this->courseModuleRepository = $courseModuleRepository;
    }

    public function execute(array $data)
    {
        $course = $this->courseModuleRepository->findCourse($data['course_id']);

        $module = $this->courseModuleRepository->createModule($course, [
            'title' => $data['title'],
            'order_index' => $data['number'],
        ]);

        if (isset($data['module_objectives'])) {
            foreach ($data['module_objectives'] as $objective) {
                $module->module_objectives()->create([
                    'number' => $objective['number'],
                    'objective' => $objective['objective'],
                ]);
            }
        }

        if (isset($data['course_objectives'])) {
            $objectiveIds = $this->courseModuleRepository->getCourseObjectiveIds($data['course_id'], $data['course_objectives']);
            $module->courseObjectives()->attach($objectiveIds);
        }

        if (isset($data['course_assessments'])) {
            foreach ($data['course_assessments'] as $assessment) {
                $createdAssessment = $module->assessments()->create([
                    'title' => $assessment['title'] ?? '',
                    'type' => $assessment['assesment_type'] ?? '',
                ]);
                if (!empty($assessment['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $assessment['aligned_module_objectives'])
                        ->pluck('id');
                    $createdAssessment->objectives()->attach($objectiveIds);
                }
            }
        }

        if (isset($data['course_instructions'])) {
            foreach ($data['course_instructions'] as $activity) {
                $createdInstruction = $module->instructions()->create([
                    'title' => $activity['title'],
                ]);
                if (!empty($activity['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $activity['aligned_module_objectives'])
                        ->pluck('id');
                    $createdInstruction->objectives()->attach($objectiveIds);
                }
            }
        }

        if (isset($data['course_materials'])) {
            foreach ($data['course_materials'] as $material) {
                $createdMaterial = $module->materials()->create([
                    'title' => $material['title'],
                ]);
                if (!empty($material['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $material['aligned_module_objectives'])
                        ->pluck('id');
                    $createdMaterial->objectives()->attach($objectiveIds);
                }
            }
        }

        if (isset($data['course_media_library_needs'])) {
            foreach ($data['course_media_library_needs'] as $need) {
                $createdNeed = $module->needs()->create(['name' => $need['name']]);
                if (!empty($need['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $need['aligned_module_objectives'])
                        ->pluck('id');
                    $createdNeed->objectives()->attach($objectiveIds);
                }
            }
        }

        return $module;
    }
}
