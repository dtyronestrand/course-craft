<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseQuizController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'questions' => 'required|array',
            'settings' => 'required|array',
            'module' => 'required',
        ]);

        $courseQuiz = \App\Models\CourseQuiz::create([
            'title' => $request->title,
            'instructions' => $request->instructions,
            'settings' => $request->settings,
            'questions' => $request->questions,
        ]);

        $maxOrderIndex = \App\Models\ModuleItem::where('course_module_id', $request->module)->max('order_index') ?? -1;

        \App\Models\ModuleItem::create([
            'course_module_id' => $request->module,
            'itemable_id' => $courseQuiz->id,
            'itemable_type' => 'quiz',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return redirect()->back()->with('success', 'Course quiz created successfully');
    }

    public function update(Request $request, \App\Models\CourseQuiz $courseQuiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'questions' => 'required|array',
            'settings' => 'required|array',
        ]);

        $courseQuiz->update([
            'title' => $request->title,
            'instructions' => $request->instructions,
            'settings' => $request->settings,
            'questions' => $request->questions,
        ]);

        return redirect()->back()->with('success', 'Course quiz updated successfully');
    }

    public function destroy(\App\Models\CourseQuiz $courseQuiz)
    {
        $courseQuiz->delete();

        return redirect()->back()->with('success', 'Course quiz deleted successfully');
    }
}
