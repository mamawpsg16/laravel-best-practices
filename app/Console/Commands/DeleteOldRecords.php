<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old records from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('tasks')->delete();
        $this->info('Old records deleted successfully.');
    }
}
