<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AppointmentService;
use Inertia\Inertia;
use App\Notifications\AppointmentInvtation;
class AppointmentController extends Controller
{
  protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function store(Request $request)
    {
        $appointment = $this->appointmentService->createAppointment($request);
        $appointment->load('host');
        if($request->has('guests')){
            $guests = $request->input('guests');
            foreach($guests as $guestId){
                $user = \App\Models\User::find($guestId);
                if($user){
                    $user->notify(new AppointmentInvtation($appointment));
                }
            }
        }
        return back()->with('success', 'Appointment created successfully.');
    }

    public function update(Request $request, $appointment)
    {
        $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->all());
        return back()->with('success', 'Appointment updated successfully.');
    }
}