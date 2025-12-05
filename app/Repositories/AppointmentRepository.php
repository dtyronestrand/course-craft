<?php

namespace App\Repositories;
use App\Models\Appointment;
use App\Models\User;


class AppointmentRepository
{

public function getForUser(User $user){
   return $user->appointments;
}

public function store(array $data)
{
    return Appointment::create([
        'host_id' => $data['$user[id]'],
        'subject' => $data['subject'],
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'notes' => $data['notes'] ?? null,
    ]);
}
public function syncGuests(Appointment $appointment, array $guestIds)
{
    $appointment->guests()->detach();
    foreach($guestIds as $id){
        if(!User::find($id)){
            throw new \Exception("User with ID $id not found.");
        }
        if($id == $appointment->host_id){
            throw new \Exception("Host cannot be a guest.");
        }
        if($appointment->guests()->where('guest_id', $id)->exists()){
            throw new \Exception("User with ID $id is already a guest.");
        }
        $appointment->guests()->attach($id);
    }
    return $appointment->guests();
}
public function update(Appointment $appointment, array $data)
{
    return $appointment->update($data);
}
public function delete(Appointment $appointment)
{
    return $appointment->destroy($appointment->id);
}
}