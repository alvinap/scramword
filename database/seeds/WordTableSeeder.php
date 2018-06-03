<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WordTableSeeder extends Seeder
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
				'name' => 'JAKARTA',
				'sort' => 1,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'BOGOR',
				'sort' => 2,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'BEKASI',
				'sort' => 3,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'DEPOK',
				'sort' => 4,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'TANGERANG',
				'sort' => 5,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'BANDUNG',
				'sort' => 6,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'SURABAYA',
				'sort' => 7,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'SEMARANG',
				'sort' => 8,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'YOGYAKARTA',
				'sort' => 9,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
			[
				'name' => 'DENPASAR',
				'sort' => 10,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			],
		];
		DB::table('word')->insert($data);
    }
}
