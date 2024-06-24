<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Fetch all users
         $users = User::limit(5)->get();

         // Iterate through users and create tasks for each
         foreach ($users as $user) {
             Task::create([
                 'user_id' => $user->id,
                 'name' => 'Sample Task for ' . $user->name,
                 'description' => 'This is a sample task description for ' . $user->name,
                 'order' => 1, // Adjust this as necessary
             ]);
         }
    }
}
