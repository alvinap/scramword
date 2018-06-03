<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
			[
				'type' => 'Correct',
				'score' => 10,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'type' => 'Wrong',
				'score' => 5,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
		];
		DB::table('score')->insert($data);
    }
}
