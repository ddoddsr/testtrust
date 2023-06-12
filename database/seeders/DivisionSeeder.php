<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(['GPR', 'Forerunner Church', 'IHOPU', 'Corporate Services', 'Finance Division' ] as $div) {
            \App\Models\Division::create([
                'name' => $div
            ]);
        }
        //GPR
        foreach([
            'Nightwatch', 'Morning Section', 'Afternoon Section', 'Evening Section', 
            'GPR Sound', 'Global Prayer Initiatives',
            'Intro', 'Simeon', 'One Thing', 'FITN', 'Immerse',
            'ANPR', 'Hope City Prayer Room', 'Israel Mandate - Isaiah 19', 
            'Mike Bickle Productions',
            'Media & Marketing',
            'Live Production',
            'Creative Video Manager',
            'Welcome Center',
            ] as $dept ) {
            \App\Models\Department::create([
                'name' => $dept,
                'division_id' => 1
            ]);
        }
        
        
        //Forerunner Church
        foreach([
            'District Pastors',
            'Pastoral Support',
            'POH', 'Counseling', 'Marriage & Family', 'Restoration and recovery',
            'Church Ministries',
            'Altar Ministry', 'Collective', 'Forerunner Youth', "Women's Ministry", "Men's Ministry", 'Next Steps'
        ] as $dept) {
            \App\Models\Department::create([
                'name' => $dept,
                'division_id' => 2  //Forerunner Church
            ]);
        }
        
        
     
        //  IHOPU  3
        foreach([
            'Finance & Administration',
            'Academics',
            'Librarian', 'Registrar', 'Academic Advising',
            'FSM', 'FSW', 'FMI',
            'Student Life',
            'Dean of Men',
            'Dean of Women',
            'Student Mobilization',
            'International Ministries',
            'Arab Ministries',
            'Chinese Ministries',
            'Korean Ministries',
            'Hispanic Ministries',
            'Portugese Ministries',
            'Russian Ministries',
            'CBETS',
            'Luke 18',
        ] as $dept) {
            \App\Models\Department::create([
                'name' => $dept,
                'division_id' => 3 //IHOPU
            ]);
        }

 
        // Corporate Services
        foreach([
            'Facilities',
            'Maintenance',
            'Building Project',
            'Grounds',
            'Housekeeping',
            'Signs',
            'Human Resources',
            'Beneifts',
            'International Affairs',
            'Staff Development',
            'FPD',
            'IT',
            'Media Engineering',
            'Sounds Engineering',
            'Security',
            'Fleet Manager',
            'Forerunner Bookstore',
            'Herrnhut',
            'Forerunner Publishing',
            'Forerunner Music',
        ] as $dept) {
            \App\Models\Department::create([
                'name' => $dept,
                'division_id' => 4 //Corporate Services
            ]);
        }

        // Finance Division
        foreach([
            'Business & Finance',
            'Accounting',
            'Partners',
            'Partners Care',
            'Partner Development',
            'Cyrus Partners',
        ] as $dept) {
            \App\Models\Department::create([
                'name' => $dept,
                'division_id' => 5 // Finance Division
            ]);
        }

    }
}
