<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use App\Services\ActivityService;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\DeliverableService;
use App\Services\DevelopmentCycleService;
use Inertia\Inertia;

class AdminController extends Controller
{
  protected $courseService;
  protected $adminSettingService;
  protected $deliverableService;
  protected $developmentCycleService;
  protected $activityService;
  
  public function __construct(CourseService $courseService, DeliverableService $deliverableService, DevelopmentCycleService $developmentCycleService, ActivityService $activityService)
  {
    $this->courseService = $courseService;
    $this->developmentCycleService = $developmentCycleService;
    $this->deliverableService = $deliverableService;
    $this->activityService = $activityService;
  }
  
  public function index()
  {
    return Inertia::render('admin/Dashboard', [
      'courseStatusCounts' => $this->courseService->courseStatusCounts(),
      'pendingCoursesCount' => $this->courseService->countPendingCourses(),
      'activeCoursesCount' => $this->courseService->countActiveCourses(),
      'recentActivities' => $this->activityService->getRecentActivities(10),
      'coursesByPrefix' => $this->courseService->coursesByPrefix(),
      'coursesNeedingAttention' => $this->courseService->coursesNeedAttention(),
    ]);
  }
  public function courses()
  {
    $courses = $this->courseService->getAllCourses();
    $developmentCycles = $this->developmentCycleService->getAllDevelopmentCycles();
    return Inertia::render('admin/Courses', ['courses' => $courses, 'developmentCycles' => $developmentCycles]);
  }
  public function settings()
  {
    $cycles = $this->developmentCycleService->getAllDevelopmentCycles();
    $deliverables = $this->deliverableService->getAllDeliverables();
    return Inertia::render('admin/Settings', ['deliverables' => $deliverables, 'cycles' => $cycles]);
  }
public function courseDetails(Course $course)
  {
    $course = $this->courseService->getCourseForAdmin($course);
    return Inertia::render('admin/CourseDetails', [
      'course' => $course,
    
    ]);
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

  public function users()
  {
    $users = User::all();
    return Inertia::render('admin/Users', ['users' => $users]);
  }
}
