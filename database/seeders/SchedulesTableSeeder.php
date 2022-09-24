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
            $date_time->setDate(2022,8, 29+$i*3)->setTime(rand(0, 23), rand(5, 59), 0);

            $quote = new DateTime;
            $quote->setTime(rand(0, 72), rand(0, 59), 0);

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
