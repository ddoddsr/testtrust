<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Database\Seeders\ShieldSeeder;
// use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sets= new \Database\Seeders\SetSeeder;
        $sets->run();
        
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        // create roles and Permissions
        Permission::create(['name' => 'access dash']);
        Permission::create(['name' => 'access tools']);
        Permission::create(['name' => 'access divisions']);
        Permission::create(['name' => 'access locations']);
        Permission::create(['name' => 'access staff']);

        $role1 = Role::create(['name' => 'Super-Admin']);
        $role2 = Role::create(['name' => 'Staff']);
        $role3 = Role::create(['name' => 'Supervisor']);
        $role4 = Role::create(['name' => 'HR Staff']);
        
        // Admin
        $role1->givePermissionTo('access dash');
        // Sam Iam
        $role2->givePermissionTo('access dash');
        // Stuart
        $role3->givePermissionTo('access dash');
        // HR
        $role4->givePermissionTo('access dash');
        $role4->givePermissionTo('access tools');
        $role4->givePermissionTo('access locations');
        $role4->givePermissionTo('access divisions');
        

        $user = User::factory()->withPersonalCompany()->create([
            'first_name' => 'Dan',
            'last_name' => 'Doddzy',
            'email' => 'dd@dd.io',
            'password' => bcrypt('asdf'),
            'active' => true,
            'is_admin' => true,
            'current_company_id' => 1,
        ]);

        $user->assignRole($role1);

        $user = User::factory()->withPersonalCompany()->create([
            'first_name' => 'Sam',
            'last_name' => 'IAM',
            'email' => 'sam@dd.io',
            'super_email1' => 'sam@dd.io',
            'password' => bcrypt('asdf'),
            'active' => true,
            'current_company_id' => 2,
        ]);
        $user->assignRole($role2);

        $user = User::factory()->withPersonalCompany()->create([
            'first_name' => 'Stuart',
            'last_name' => 'Greaves',
            'email' => 'stuartgreaves@ihopkc.org',
            'password' => bcrypt('asdf'),
            'active' => true,
            'is_admin' => false,
            'is_supervisor' => true,
            'current_company_id' => 3,
        ]);
        $user->assignRole($role3);
        $user = User::factory()->withPersonalCompany()->create([
            'first_name' => 'HR',
            'last_name' => 'Excellance',
            'email' => 'hr@ihopkc.org',
            'password' => bcrypt('2474u'),
            'active' => true,
            'is_admin' => true,
            'is_supervisor' => true,
            'current_company_id' => 4,
        ]);        
        $user->assignRole($role4);
        // TODO Better Alias seeder?
        \App\Models\EmailAlias::create([
        'user_id' => 1,    
        'email' => 'dandodd@ihopkc.org'
        ]);
        \App\Models\EmailAlias::create([
        'user_id' => 3,    
        'email' => 'stuartgreaves@pm.me'
        //         'stuartgreaves@pm.me
        ]);

        
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

        $this->call(DivisionSeeder::class);
    }
}