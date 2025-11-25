<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Http\Request;

use App\Services\DeliverableService;
use App\Services\DevelopmentCycleService;
use Inertia\Inertia;

class AdminController extends Controller
{
  protected $courseService;
  protected $adminSettingService;
  protected $deliverableService;
  protected $developmentCycleService;
  public function __construct(CourseService $courseService,  DeliverableService $deliverableService, DevelopmentCycleService $developmentCycleService)
  {
    $this->courseService = $courseService;
    $this->developmentCycleService = $developmentCycleService;
    $this->deliverableService = $deliverableService;
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
    $cycles = $this->developmentCycleService->getAllDevelopmentCycles();
    $deliverables = $this->deliverableService->getAllDeliverables();
    return Inertia::render('admin/Settings', ['deliverables' => $deliverables, 'cycles' => $cycles]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'cycle_name' => 'required|string|max:255',
      'cycle_start' => 'required|date',
      'cycle_end' => 'required|date',
    ]);

    // Assuming you have a Setting model to store admin settings
    $this->developmentCycleService->updateOrCreateDevelopmentCycle([
        'name' => $request->input('cycle_name'),
        'start_date' => $request->input('cycle_start'),
        'end_date' => $request->input('cycle_end'),
    ]);

    return back()->with('success', 'Settings updated successfully.');
  }
}
