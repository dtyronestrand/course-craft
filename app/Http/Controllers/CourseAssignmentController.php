<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Services\CourseAssignmentService;
use Illuminate\Http\Request;

class CourseAssignmentController extends Controller
{
    protected $courseAssignmentService;

    public function __construct(CourseAssignmentService $courseAssignmentService)
    {
        $this->courseAssignmentService = $courseAssignmentService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'required|string',
            'criteria' => 'required|string',
            'settings' => 'required|array',
            'module' => 'required',
        ]);

        $this->courseAssignmentService->createAssignment($request->all());

        return redirect()->back()->with('success', 'Course assignment created successfully');
    }

    public function update(Request $request, CourseAssignment $courseAssignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'required|string',
            'criteria' => 'required|string',
            'settings' => 'required|array',
        ]);

        $this->courseAssignmentService->updateAssignment($courseAssignment, $request->all());

        return redirect()->back()->with('success', 'Course assignment updated successfully');
    }

    public function destroy(CourseAssignment $courseAssignment)
    {
        $this->courseAssignmentService->deleteAssignment($courseAssignment);

        return redirect()->back()->with('success', 'Course assignment deleted successfully');
    }
}
