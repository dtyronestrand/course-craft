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
    return Inertia::render('admin/Dashboard');
  }
  public function courses()
  {
    $courses = $this->courseService->getAllCourses();
    return Inertia::render('admin/Courses', ['courses' => $courses]);
  }
}
