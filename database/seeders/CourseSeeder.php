<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
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
     ->each(function($course) use ($users, $roles){
        $usersToAttach = $users->shuffle()->take(rand(1, 4));
        $pivotData = [];
        $assignedRoles = Arr::shuffle($roles);
        foreach($usersToAttach as $index => $user){
            $role = $assignedRoles[$index];
            $pivotData[$user->id] = ['role' => $role];
            if ($index >= count($roles) - 1) {
                break;
            }
        }
        $course->users()->attach($pivotData);
    });
    }


}
