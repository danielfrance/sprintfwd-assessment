<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Member;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        if (User::count() === 0) {
            
            User::create([
                'name' => 'Base Admin',
                'email' => env('BASE_ADMIN_EMAIL'),
                'password' => bcrypt(env('BASE_ADMIN_PASSWORD')),
            ]);
        }

        Team::factory(10)->create()->each(function($team){
            $team->members()->saveMany(Member::factory(3)->create([
                'team_id' => $team->id,
            ]));
        });

        Project::factory(10)->create();


    }


    
}
