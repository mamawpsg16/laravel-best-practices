<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) { 
            Author::factory()
                ->has(Post::factory()->count(3))
                ->create();
        }
    }


    
}
