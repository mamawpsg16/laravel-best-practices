<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UnbanUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unban-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unban users whose ban period has expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNotNull('banned_at')
        ->where('banned_at', '<', Carbon::now())
        ->get();
        Log::debug($users);
        foreach ($users as $user) {
            $user->banned_at = null;
            $user->banned_description = null;
            $user->banned_days = null;
            $user->save();
        }

        $this->info($this->description);
    }
}
