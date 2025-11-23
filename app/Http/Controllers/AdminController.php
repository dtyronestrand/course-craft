<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\AdminSettingService;
use Inertia\Inertia;

class AdminController extends Controller
{
  protected $courseService;
  protected $adminSettingService;
  public function __construct(CourseService $courseService, AdminSettingService $adminSettingService)
  {
    $this->courseService = $courseService;
    $this->adminSettingService = $adminSettingService;
    
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
  public function settings()
  {
    $settings = $this->adminSettingService->getAllSettings();
    return Inertia::render('admin/Settings', ['settings' => $settings]);
  }
}
