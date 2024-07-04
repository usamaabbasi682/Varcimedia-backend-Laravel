<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $users = User::factory(3)->create();
        $users->each(function($user) {
            $user->assignRole('client');

            $projects = Project::factory(rand(2,3))->create([
                'user_id' => $user->id,
            ]);

            $projects->each(function($project) use($user) {
                $project->user_projects()->attach([1,2,3,4]);
            });
        });
    }
}
