<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use App\Services\ActivityService;
use App\Models\Course;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
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
  protected $userService;
  
  public function __construct(CourseService $courseService, DeliverableService $deliverableService, DevelopmentCycleService $developmentCycleService, ActivityService $activityService, UserService $userService)
  {
    $this->courseService = $courseService;
    $this->developmentCycleService = $developmentCycleService;
    $this->deliverableService = $deliverableService;
    $this->activityService = $activityService;
    $this->userService = $userService;
  }
  
  public function index()
  {
    return Inertia::render('admin/Dashboard', [
      'courseStatusCounts' => $this->courseService->courseStatusCounts(),
      'pendingCoursesCount' => $this->courseService->countPendingCourses(),
      'activeCoursesCount' => $this->courseService->countActiveCourses(),
      'recentActivities' => $this->activityService->getRecentActivities(10),
      'coursesNeedingAttention' => $this->courseService->coursesNeedAttention(),
      'courses' => $this->courseService->getAllCourses(),
      'avgCompletionTime' => $this->courseService->averageCompletionRate(),
    ]);
  }

  public function calendar()
  {
    return Inertia::render('admin/Calendar', [
      'courses' => $this->courseService->getAllCourses(),
      'developmentCycles' => $this->developmentCycleService->getAllDevelopmentCycles(),
    ]);
  }

  public function courses()
  {
    $courses = $this->courseService->getAllWithRelations();
    $developmentCycles = $this->developmentCycleService->getAllDevelopmentCycles();
    return Inertia::render('admin/Courses', ['courses' => $courses, 'developmentCycles' => $developmentCycles]);
  }
  public function settings()
  {
    $cycles = $this->developmentCycleService->getAllDevelopmentCycles();
    $deliverables = $this->deliverableService->getAllDeliverables();
    $capacity = AdminSetting::first()?->capacity ?? 5;
    return Inertia::render('admin/Settings', ['deliverables' => $deliverables, 'cycles' => $cycles, 'capacity' => $capacity]);
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
    $users = $this->userService->getUsersWorkloads();

    return Inertia::render('admin/Users', ['users' => $users, ]);
  }

  public function userWorkloads()
  {
    $workloads = $this->userService->getUsersWorkloads();
    return response()->json($workloads);
  }

  public function allUsers()
  {
      $users = User::select('id', 'first_name', 'last_name')->get();
      return response()->json($users);
  }

}
