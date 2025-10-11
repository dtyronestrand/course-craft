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

        $module = CourseModule::create([
            'course_id' => $request->course_id,
            'title' => $request->moduleName,
            'order_index' => $request->moduleNumber,
            'learning_objectives' => json_encode($request->moduleObjectives ?? []),
            'aligned_course_objectives' => json_encode($request->alignedCourseObjectives ?? []),
        ]);

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