<?php

namespace App\Actions\CourseModules;

use App\Models\CourseModule;
use App\Repositories\CourseModuleRepository;

class UpdateCourseModuleAction
{
    protected $courseModuleRepository;

    public function __construct(CourseModuleRepository $courseModuleRepository)
    {
        $this->courseModuleRepository = $courseModuleRepository;
    }

    public function execute(CourseModule $courseModule, array $data)
    {
        $this->courseModuleRepository->updateModule($courseModule, [
            'title' => $data['title'],
            'order_index' => $data['number'],
        ]);

        $courseModule->module_objectives()->delete();
        if (isset($data['module_objectives'])) {
            foreach ($data['module_objectives'] as $moduleObjective) {
                $courseModule->module_objectives()->create([
                    'number' => $moduleObjective['number'],
                    'objective' => $moduleObjective['objective'],
                ]);
            }
        }

        if (isset($data['course_objectives'])) {
            $objectiveIds = $this->courseModuleRepository->getCourseObjectiveIds($data['course_id'], $data['course_objectives']);
            $courseModule->courseObjectives()->sync($objectiveIds);
        } else {
            $courseModule->courseObjectives()->detach();
        }

        $courseModule->assessments()->delete();
        if (isset($data['course_assessments'])) {
            foreach ($data['course_assessments'] as $assessment) {
                $createdAssessment = $courseModule->assessments()->create([
                    'title' => $assessment['title'] ?? '',
                    'type' => $assessment['assesment_type'] ?? '',
                ]);
                if (!empty($assessment['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $assessment['aligned_module_objectives'])
                        ->pluck('id');
                    $createdAssessment->objectives()->attach($objectiveIds);
                }
            }
        }
        $courseModule->instructions()->delete();
        if (isset($data['course_instructions'])) {
            foreach ($data['course_instructions'] as $activity) {
                $createdInstruction = $courseModule->instructions()->create([
                    'title' => $activity['title'],
                    'type' => $activity['type'] ?? '',
                ]);
                if (isset($activity['aligned_module_objectives']) && !empty($activity['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $activity['aligned_module_objectives'])
                        ->pluck('id');
                    $createdInstruction->objectives()->attach($objectiveIds);
                }
            }
        }

        $courseModule->materials()->delete();
        if (isset($data['course_materials'])) {
            foreach ($data['course_materials'] as $material) {
                $createdMaterial = $courseModule->materials()->create([
                    'title' => $material['title'],
                    'course_id' => $courseModule->course_id,
                    'type' => $material['type'] ?? '',
                ]);
                if (!empty($material['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $material['aligned_module_objectives'])
                        ->pluck('id');
                    $createdMaterial->objectives()->attach($objectiveIds);
                }
            }
        }

        $courseModule->needs()->delete();
        if (isset($data['course_media_library_needs'])) {
            foreach ($data['course_media_library_needs'] as $need) {
                $createdNeed = $courseModule->needs()->create(['name' => $need['name']]);
                if (!empty($need['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $need['aligned_module_objectives'])
                        ->pluck('id');
                    $createdNeed->objectives()->attach($objectiveIds);
                }
            }
        }

        return $courseModule;
    }
}
