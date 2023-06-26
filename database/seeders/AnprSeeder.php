<?php

namespace Database\Seeders;

use App\Models\Set;
use App\Models\User;
use App\Models\Schedule;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnprSeeder extends Seeder
{


    public function __construct()
    {

    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $intercessionSets = Set::intercessionSets();

        // $sequence = 1;
        // $faker = new FAKER;
        
        // $day = 'Sunday';
        // foreach (Set::setOfDay() as $setOfDay) {
        //     if($setOfDay == '10am') {
        //         break; 
        //     }
        //     $set = [
        //         'dayOfWeek' => $day,
        //         'setOfDay' => $setOfDay,
        //         'location' => 'ANPR',
        //         'location_id' => 2,
        //         'worship_leader_id' => $faker->numberBetween(30, 50),
        //         'associate_worship_leader_id' => $faker->numberBetween(51, 75),
        //         'prayer_leader_id' => $faker->numberBetween(76, 100),
        //         'section_leader_id' => $faker->numberBetween(3, 29),
        //         'title' => in_array($setOfDay, $intercessionSets)  ? 'Intercession' : 'Worship with the Word',
        //         'sequence' => $sequence++,
        //         'active' => true,
        //     ];
        //     $result = Set::create($set);
        // }
            
        // DELETE FROM schedules where `location` = "ANPR"  
        // echo 'DELETE FROM schedules where `location` = "ANPR"' | mysql testtrust
        // echo 'DELETE FROM schedules where `location` = "ANPR"' | mysql testtrust &&  art db:seed AnprSeeder
        $staffCount = 1;
        foreach(User::whereIn('designation_id', [1,2,5,7])
            ->where('supervisor', '!=', null)->get()
        as $staffRecord ) {
        // where('supervisor' != null) as $staff) {
            if ( $staffCount < 181) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '12:00AM',
                            'end' => '10:00AM',
                            'location' => 'ANPR',
                            'location_id' => 2,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->first_name . ' ' . $staffCount); 
            } elseif ( $staffCount >= 181 && $staffCount < 210) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '2:00AM',
                            'end' => '10:00AM',
                            'location' => 'ANPR',
                            'location_id' => 2,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->last_name . ' ' . $staffCount);
            } elseif ( $staffCount >= 211 && $staffCount < 320) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '4:00AM',
                            'end' => '10:00AM',
                            'location' => 'ANPR',
                            'location_id' => 2,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);

                logger($staffRecord->email . ' ' . $staffCount);
            } elseif ( $staffCount >= 321 && $staffCount < 350) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '6:00AM',
                            'end' => '10:00AM',
                            'location' => 'ANPR',
                            'location_id' => 2,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);

                logger($staffRecord->email);    
            } elseif ( $staffCount >= 351 && $staffCount < 450) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '8:00AM',
                            'end' => '10:00AM',
                            'location' => 'ANPR',
                            'location_id' => 2,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);

                logger($staffRecord->email);    
            } else {
                logger('More that 450'  . ' ' . $staffCount);
            }

            
            $staffCount++ ;
        }    
    }
}
