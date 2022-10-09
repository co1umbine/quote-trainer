<?php

namespace Database\Seeders;

use App\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['研究', '趣味', '論文執筆', '得意', '苦手'];
        $colors = ['a7cbd9', 'b5a7d9', 'd9a7cb', 'd9b5a7', 'cbd9a7'];
        for($i = 1; $i <= 5; ++$i){
            Tag::create([
                'user_id' => 1,
                'name' => $names[$i-1],
                'color' => $colors[$i-1],
            ]);
        }
    }
}
