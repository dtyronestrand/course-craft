<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $courses = $this->courseService->getCoursesForUser($request->user());
        $view = $request->user()->is_admin ? 'admin/Courses' : 'Dashboard';

        return Inertia::render($view, ['courses' => $courses]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'number' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'objectives' => 'nullable|array',
            'users' => 'nullable|array',
        ]);

        $this->courseService->createCourse($request->all());

        return to_route('dashboard');
    }

    public function show(Course $course)
    {
        $course = $this->courseService->getCourseWithDetails($course);
        return Inertia::render('courses/Show', [
            'course' => $course,
            'numberOfModules' => $course->modules()->count(),
        ]);
    }

    public function map(Course $course)
    {
        $course = $this->courseService->getCourseForMap($course);
        return Inertia::render('courses/Map', [
            'course' => $course,
            'numberOfModules' => $course->modules()->count(),
        ]);
    }

    public function storyboard(Course $course)
    {
        $course = $this->courseService->getCourseForStoryboard($course);
        return Inertia::render('courses/Storyboard', [
            'course' => $course,
            'numberOfModules' => $course->modules()->count(),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'number' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'objectives' => 'nullable|array',
            'users' => 'nullable|array',
        ]);

        $this->courseService->updateCourse($course, $request->all());

        return to_route('dashboard');
    }

    public function destroy(Course $course)
    {
        $this->courseService->deleteCourse($course);

        return to_route('dashboard');
    }
}
