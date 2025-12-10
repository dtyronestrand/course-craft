<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Deliverable;
use Illuminate\Support\Arr;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     $roles =['Designer', 'SME','Builder','Manager'];
     $users = User::factory()->count(20)->create();

     Course::factory()
     ->count(5)
     ->create()
     ->each(function($course){
        $course->developmentCycle()->associate(\App\Models\DevelopmentCycle::inRandomOrder()->first());
        $course->save();
        
        $deliverables = Deliverable::all();
        $pivotData = [];
        foreach($deliverables as $deliverable){
            $pivotData[$deliverable->id] = [
                'due_date' => $course->developmentCycle->start_date->addDays($deliverable->template_days_offset)
            ];
        }
        $course->deliverables()->attach($pivotData);
     })
     ->each(function($course) use ($users, $roles){
        $usersToAttach = $users->shuffle()->take(rand(1, 4));
        $pivotData = [];
        $assignedRoles = Arr::shuffle($roles);
        foreach($usersToAttach as $index => $user){
            $role = $assignedRoles[$index % count($roles)];
            $pivotData[$user->id] = ['role' => $role];
        }
        $course->users()->attach($pivotData);
    });
    }


}
