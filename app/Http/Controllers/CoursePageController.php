<?php

namespace App\Http\Controllers;

use App\Models\CoursePage;
use App\Services\CoursePageService;
use Illuminate\Http\Request;

class CoursePageController extends Controller
{
    protected $coursePageService;

    public function __construct(CoursePageService $coursePageService)
    {
        $this->coursePageService = $coursePageService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'module' => 'required',
        ]);

        $this->coursePageService->createPage($request->all());

        return redirect()->back()->with('success', 'Course page created successfully');
    }

    public function update(Request $request, CoursePage $coursePage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $this->coursePageService->updatePage($coursePage, $request->all());

        return redirect()->back()->with('success', 'Course page updated successfully');
    }

    public function destroy(CoursePage $coursePage)
    {
        $this->coursePageService->deletePage($coursePage);

        return redirect()->back()->with('success', 'Course page deleted successfully');
    }
}
