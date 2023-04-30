<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ShieldSeeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sets= new \Database\Seeders\SetSeeder;
        $sets->run();
        
        \App\Models\User::factory()->withPersonalCompany()->create([
            'first_name' => 'Dan',
            'last_name' => 'Dodd',
            'email' => 'dd@dd.io',
            'password' => bcrypt('asdf'),
            'active' => true,
        ]);
        \App\Models\User::factory()->withPersonalCompany()->create([
            'first_name' => 'Sam',
            'last_name' => 'IAM',
            'email' => 'sam@dd.io',
            'password' => bcrypt('asdf'),
            'active' => true,
        ]);
        // $shield = new ShieldSeeder;
        // $shield->run();
       
        // Artisan::call('shield:super-admin --user=1');

        // \App\Models\User::factory(15000)->withPersonalCompany()->create();
    }
}
