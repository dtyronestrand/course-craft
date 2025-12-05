<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    protected $calendarService;
    protected $userService;

    public function __construct(CalendarService $calendarService, UserService $userService)
    {
        $this->calendarService = $calendarService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        return Inertia::render('admin/Calendar', [
            'events' => $this->calendarService->getUserAppointments($request->user()),
            'users' => $this->userService->getAllUsersForCalendar(),
        ]);
    }
}