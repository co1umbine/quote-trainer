<?php

namespace Database\Seeders;

use App\Experience;
use Illuminate\Database\Seeder;
use DateTimeImmutable;

class ExperiencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <=10; $i++){
            $date_time = new DateTimeImmutable('2022-09-' . $i*3 . 'T' . rand(0, 23) . ':' . rand(0, 59) . ':00');
    
            $quote = rand(0, 168) * 3600000 + rand(0, 59) * 60000;
            $period = $quote + rand(-5, 5) * 3600000 + rand(-59, 59) * 60000;
    
            $end_date_time = $date_time->modify(floor($period/3600000) . ' hours' )->modify(floor(($period%3600000)/60000) . ' minutes');

            Experience::create([
                'user_id' => 1,
                'schedule_id' => $i,
                'name' => 'experience' . $i,
                'color' => 'FFFFFF',
                'start_on' => $date_time,
                'end_on' => $end_date_time,
                'quote' => $quote,
                'period' => $period,
                'efficiency' => rand(0, 5),
                'note' => 'note' . $i,
            ]);
        }
    }
}
