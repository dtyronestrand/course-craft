<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use Illuminate\Http\Request;

class CourseModuleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'moduleName' => 'required|string|max:255',
            'moduleNumber' => 'required|integer',
            'moduleObjectives' => 'nullable|array',
            'alignedCourseObjectives' => 'nullable|array',
            'course_assessments' => 'nullable|array',
            'instructional_activities' => 'nullable|array',
            'instructionalMaterials' => 'nullable|array',
            'mediaLibraryNeeds' => 'nullable|array',
        ]);

        $course = \App\Models\Course::findOrFail($request->course_id);
        $module = $course->modules()->create([
            'title' => $request->moduleName,
            'order_index' => $request->moduleNumber,
            'module_objectives' => json_encode($request->moduleObjectives ?? []),
        ]);

        // Attach aligned course objectives to pivot table
        if ($request->alignedCourseObjectives) {
            $objectiveIds = \App\Models\CourseObjective::whereIn('number', $request->alignedCourseObjectives)
                ->where('course_id', $request->course_id)
                ->pluck('id');
            $module->courseObjectives()->attach($objectiveIds);
        }

        // Create assessments
        if ($request->course_assessments) {
            foreach ($request->course_assessments as $assessment) {
                $module->assessments()->create();
            }
        }

        // Create instructional activities
        if ($request->instructional_activities) {
            foreach ($request->instructional_activities as $activity) {
                $module->instructions()->create(['name' => $activity]);
            }
        }

        // Create materials
        if ($request->instructionalMaterials) {
            foreach ($request->instructionalMaterials as $material) {
                $module->materials()->create(['name' => $material]);
            }
        }

        // Create media/library needs
        if ($request->mediaLibraryNeeds) {
            foreach ($request->mediaLibraryNeeds as $need) {
                $module->mediaLibraryNeeds()->create(['name' => $need]);
            }
        }

        return redirect()->back()->with('success', 'Module created successfully');
    }
}