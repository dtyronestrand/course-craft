<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
  protected $courseService;
  public function __construct(CourseService $courseService)
  {
    $this->courseService = $courseService;
  }
  public function index()
  {
    $activeCoursesCount = $this->courseService->countActiveCourses();
    return Inertia::render('admin/Dashboard', ['activeCoursesCount' => $activeCoursesCount]);
  }
  public function courses()
  {
    $courses = $this->courseService->getAllCourses();
    return Inertia::render('admin/Courses', ['courses' => $courses]);
  }
}
