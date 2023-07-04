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
        $brkpt = [181, 211, 246, 286, 351, 411];
        
        $staffCount = 1;
        foreach(User::whereIn('designation_id', [1,2,5,7])
            ->where('supervisor', '!=', null)->get()
        as $staffRecord ) {
        // where('supervisor' != null) as $staff) {
            if ( $staffCount < $brkpt[0]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '12:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->first_name . ' ' . $staffCount); 
            } elseif ( $staffCount >= $brkpt[0] && $staffCount < $brkpt[1]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '2:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->last_name . ' ' . $staffCount);
            } elseif ( $staffCount >= $brkpt[1] && $staffCount < $brkpt[2]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '4:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->email . ' ' . $staffCount);
            } elseif (  $staffCount >= $brkpt[2] && $staffCount < $brkpt[3]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '6:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->email);    
            } elseif ( $staffCount >= $brkpt[3] && $staffCount < $brkpt[4]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '8:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->email);    
            } elseif ( $staffCount >= $brkpt[4] && $staffCount < $brkpt[5]) {
                $scheds =
                    new Schedule(
                        [
                            'day' => 'Sunday',
                            'start' => '8:00AM',
                            'end' => '10:00AM',
                            'location' => 'TEST',
                            'location_id' => 6,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
                logger($staffRecord->email);
            } else {
                logger('More than: ' . $brkpt[5] . ':  ' . $staffCount);
            }

            
            $staffCount++ ;
        }    
    }
}
