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
        for ($i = 0; $i < 10; $i++) {
            $date_time = new DateTime();
            $date_time->setDate(2022,9, 29+$i*3)->setTime(rand(0, 23), rand(5, 59), 0);

            $quote = rand(0, 168) * 3600000 + rand(0, 59) * 60000;

            Schedule::create([
                'user_id' => 1,
                'name' => 'name' . $i,
                'color' => 'FFFFFF',
                'start_on' => $date_time,
                'quote' => $quote,
                'note' => 'note' . $i,
            ]);
        }
    }
}
