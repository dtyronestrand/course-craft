<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use App\Services\CourseModuleService;
use Illuminate\Http\Request;

class CourseModuleController extends Controller
{
    protected $courseModuleService;

    public function __construct(CourseModuleService $courseModuleService)
    {
        $this->courseModuleService = $courseModuleService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
            'module_objectives' => 'nullable|array',
            'course_objectives' => 'nullable|array',
            'course_assessments' => 'nullable|array',
            'course_instructions' => 'nullable|array',
            'course_materials' => 'nullable|array',
            'course_media_library_needs' => 'nullable|array',
        ]);

        $this->courseModuleService->createModule($request->all());

        return redirect()->back()->with('success', 'Module created successfully');
    }

    public function update(Request $request, CourseModule $courseModule)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
            'module_objectives' => 'nullable|array',
            'course_objectives' => 'nullable|array',
            'course_assessments' => 'nullable|array',
            'course_instructions' => 'nullable|array',
            'course_materials' => 'nullable|array',
            'course_media_library_needs' => 'nullable|array',
        ]);

        $this->courseModuleService->updateModule($courseModule, $request->all());

        return redirect()->back()->with('success', 'Module updated successfully');
    }

    public function destroy(CourseModule $courseModule)
    {
        $this->courseModuleService->deleteModule($courseModule);

        return redirect()->back()->with('success', 'Module deleted successfully');
    }
}
