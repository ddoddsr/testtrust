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
            'last_name' => 'Doddzy',
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
        \App\Models\User::factory()->withPersonalCompany()->create([
            'first_name' => 'Stuart',
            'last_name' => 'Greaves',
            'email' => 'stuartgreaves@ihopkc.org',
            'password' => bcrypt('asdf'),
            'active' => true,
        ]);

        // TODO Better Alias seeder?
        \App\Models\EmailAlias::create([
        'user_id' => 1,    
        'email' => 'dandodd@ihopkc.org'
        ]);
        \App\Models\EmailAlias::create([
        'user_id' => 3,    
        'email' => 'stuartgreaves@pm.me'
        ]);
/* 
Elijah Choi	
    elijahchoi@ihopkc.org
    jaeeunchoi@ihopkc.org
    Elijah Choi	ejijahchoi@ihopkc.org
    Elijah Choi	jaeunchoi@ihopkc.org

*/
        // $shield = new ShieldSeeder;
        // $shield->run();
       
        // Artisan::call('shield:super-admin --user=1');

        // \App\Models\User::factory(15000)->withPersonalCompany()->create();
        // Locations
        foreach (['GPR', 'ANPR', 'FC', 'HOPE City','Malichai 6:6' ] as $location) {
                    \App\Models\Location::create([
                'name' => $location
            ]);
        }

        //TODO create divisions and departments

    }
}