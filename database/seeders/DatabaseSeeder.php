<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->withPersonalCompany()->create([
            'first_name' => 'Dan',
            'last_name' => 'Dodd',
            'email' => 'dd@dd.io',
            'password' => bcrypt('asdf'),
            
        ]);
        \App\Models\User::factory()->withPersonalCompany()->create([
            'first_name' => 'Sam',
            'last_name' => 'Iam',
            'email' => 'sam@dd.io',
            'password' => bcrypt('asdf'),
            
        ]);
        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
