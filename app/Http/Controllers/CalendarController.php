<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use App\Services\AppointmentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    protected $calendarService;
    protected $userService;
    protected $appointmentService;

    public function __construct(CalendarService $calendarService, AppointmentService $appointmentService, UserService $userService)
    {
        $this->calendarService = $calendarService;
        $this->userService = $userService;
        $this->appointmentService = $appointmentService;
    }
    public function index(Request $request)
    {
        return Inertia::render('admin/Calendar', [
            'appointments' => $this->appointmentService->getUserAppointments(Auth::user())->toArray(),
            'users' => $this->userService->getAllUsersForCalendar(),
        ]);
    }
}