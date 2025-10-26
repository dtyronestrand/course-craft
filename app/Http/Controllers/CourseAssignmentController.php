<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseAssignment;
use App\Models\ModuleItem;

class CourseAssignmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'required|string',
            'criteria' => 'required|string',
            'settings' => 'required|array',
            'module' => 'required',
        ]);

        $courseAssignment = CourseAssignment::create([
            'title' => $request->title,
            'purpose' => $request->purpose,
            'criteria' => $request->criteria,
            'settings' => $request->settings,
        ]);

        $maxOrderIndex = ModuleItem::where('course_module_id', $request->module)->max('order_index') ?? -1;

        ModuleItem::create([
            'course_module_id' => $request->module,
            'itemable_id' => $courseAssignment->id,
            'itemable_type' => 'assignment',
            'order_index' => $maxOrderIndex + 1,
        ]);

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

        $courseAssignment->update([
            'title' => $request->title,
            'purpose' => $request->purpose,
            'criteria' => $request->criteria,
            'settings' => $request->settings,
        ]);

        return redirect()->back()->with('success', 'Course assignment updated successfully');
    }

    public function destroy(CourseAssignment $courseAssignment)
    {
        $courseAssignment->delete();

        return redirect()->back()->with('success', 'Course assignment deleted successfully');
    }
}
