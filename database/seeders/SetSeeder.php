<?php

namespace Database\Seeders;

use App\Models\Set;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetSeeder extends Seeder
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
        $intercessionSets = ['12am', '4am', '6am', '10am', '4pm', '8pm'];
        $setOfDay = [
             '12am',
             '2am',
             '4am',
             '6am',
             '8am',
             '10am',
             '12pm',
             '2pm',
             '4pm',
             '6pm',
             '8pm',
             '10pm',
         ];

        $dayOfWeek = [
            'Sunday', 'Monday',  'Tuesday',  'Wednesday',  'Thursday', 'Friday', 'Saturday'];
        $sequence = 1;

        //  $sets = [];
         $sequence=1;
         $faker = new FAKER;
         foreach ($dayOfWeek as $day) {
             foreach ($setOfDay as $set) {
                 $set = [
                     'dayOfWeek' => $day,
                     'setOfDay' => $set,
                     'location' => 'GPR',
                     'worship_leader_id' => $faker->numberBetween(30, 50),
                     'associate_worship_leader_id' => $faker->numberBetween(51, 75),
                     'prayer_leader_id' => $faker->numberBetween(76, 100),
                     'section_leader_id' => $faker->numberBetween(3, 29),
                     'title' => in_array($set, $intercessionSets)  ? 'Intercession' : 'Worship with the Word',
                     'sequence' => $sequence++,
                     'active' => true,
                 ];
                 $result = Set::create($set);
                 logger($result);
             }
         }

    }
}
