<?php

namespace App\Console\Commands;

use App\Models\CourseDeliverable;
use Illuminate\Console\Command;

class MarkMissedDeadlineCommand extends Command
{
    protected $signature = 'courses:markMissedDeadlines';

    protected $description ='Checks for courses deliverables that have missed their deadlines and marks them accordingly';

    public function handle()
    {
        $count = CourseDeliverable::where('is_done', false)
            ->where('due_date', '<', now())
            ->where('missed_due_date_count', 0)
            ->update(['missed_due_date_count' => 1]);

        if ($count > 0) {
            $this->info("Marked {$count} deliverables as missed.");
        } else {
            $this->info('No deliverables to mark.');
        }

        return Command::SUCCESS;
    }
}