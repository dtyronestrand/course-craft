<?php

namespace App\Http\Controllers;

use App\Models\CourseQuiz;
use App\Services\CourseQuizService;
use Illuminate\Http\Request;

class CourseQuizController extends Controller
{
    protected $courseQuizService;

    public function __construct(CourseQuizService $courseQuizService)
    {
        $this->courseQuizService = $courseQuizService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'questions' => 'required|array',
            'settings' => 'required|array',
            'module' => 'required',
        ]);

        $this->courseQuizService->createQuiz($request->all());

        return redirect()->back()->with('success', 'Course quiz created successfully');
    }

    public function update(Request $request, CourseQuiz $courseQuiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'questions' => 'required|array',
            'settings' => 'required|array',
        ]);

        $this->courseQuizService->updateQuiz($courseQuiz, $request->all());

        return redirect()->back()->with('success', 'Course quiz updated successfully');
    }

    public function destroy(CourseQuiz $courseQuiz)
    {
        $this->courseQuizService->deleteQuiz($courseQuiz);

        return redirect()->back()->with('success', 'Course quiz deleted successfully');
    }
}
