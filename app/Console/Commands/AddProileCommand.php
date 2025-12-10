<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;

class AddProileCommand extends Command
{
    protected $signature = 'profiles:add';

    protected $description = 'Adds profiles for users who do not have one';

    public function handle()
    {
        $usersWithoutProfiles = User::doesntHave('profile')->get();

        $count = $usersWithoutProfiles->count();
        if ($count > 0) {
            $this->info("Adding profiles for {$count} users...");

            foreach ($usersWithoutProfiles as $user) {
                Profile::create([
                    'user_id' => $user->id,
                ]);
            }

            $this->info("Added profiles for {$count} users.");
        } else {
            $this->info("All users already have profiles.");
        }

        return Command::SUCCESS;
    }
}