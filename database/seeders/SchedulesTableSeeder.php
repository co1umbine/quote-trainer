<?php

namespace Database\Seeders;

use App\Schedule;
use Illuminate\Database\Seeder;
use DateTime;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Schedule::create([
                'user_id' => 1,
                'name' => 'name' . $i,
                'color' => 'FFFFFF',
                'start_on' => new DateTime(),
                'quote' => new DateTime(),
                'note' => 'note' . $i,
            ]);
        }
    }
}
