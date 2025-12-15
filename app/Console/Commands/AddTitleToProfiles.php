<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profile;
use App\Titles;

class AddTitleToProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-title-to-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds titles to profiles that do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $profiles = Profile::all()->whereNull('title');

        $count = $profiles->count();
        if ($count > 0) {
            $this->info("Adding titles to {$count} profiles...");

            foreach ($profiles as $profile) {
              $profile->update([
                  'title' => fake()->randomElement(Titles::cases())->value
                ]);
            }

            $this->info("Added titles for {$count} profiles.");
        } else {
            $this->info("All profiles already have titles.");
        }

    }
}
