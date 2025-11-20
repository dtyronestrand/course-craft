<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseMapController extends Controller
{
    public function show(Course $course)
    {
        $courseData = $course->load([
            'modules.assessments.objectives',
            'modules.materials.objectives', 
            'modules.instructions.objectives'
        ]);

        return view('courses.map', compact('courseData'));
    }

    public function storyboard(Course $course)
    {
        $courseData = $course->load([
            'modules.assessments.objectives',
            'modules.materials.objectives',
            'modules.instructions.objectives'
        ]);

        return view('courses.storyboard', compact('courseData'));
    }

    public function data(Course $course)
    {
        return response()->json([
            'course' => $course->only(['id', 'title', 'description']),
            'modules' => $course->modules()->with(['assessments.objectives', 'materials.objectives', 'instructions.objectives'])
                ->orderBy('order_index')
                ->get()
        ]);
    }
}