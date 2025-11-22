<?php

namespace App\Console\Commands;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActivateCoursesCommand extends Command
{
    protected $signature = 'courses:activate';

    protected $description ='Checks for pending courses whose start date has been reached and sets their status to active';

    public function handle()
    {
        $now = Carbon::now();
        $coursesToActivate = Course::where('status', 'pending')
            ->where('start_date', '<=', $now)
            ->get();

            $count = $coursesToActivate->count();
            if ($count >0){
                $this->info("Activating {$count} courses...");
                Course::whereIn('id', $coursesToActivate->pluck('id'))->update(['status' => 'active']);
                $this->info("Activated {$count} courses.");
            }else{
                $this->info("No courses to activate.");
            }
            return Command::SUCCESS;
    }
}