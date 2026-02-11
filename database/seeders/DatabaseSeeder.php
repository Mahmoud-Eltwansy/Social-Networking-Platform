<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $users = User::factory()->count(10)->create();

            $posts = Post::factory()->count(20)->recycle($users)->create();
            foreach ($posts as $post) {
                $post->images()->create([
                    'path' => fake()->image(storage_path('app/public/posts'), 640, 480, null, false)
                ]);
            }
        });
    }
}
