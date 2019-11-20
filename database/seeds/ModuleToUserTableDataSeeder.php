<?php

use Illuminate\Database\Seeder;

class ModuleToUserTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('moduleToUser')->insert([
         	[
	            'id' => 5590,
	            'module_id' => 1,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5591,
	            'module_id' => 1,
	            'user_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 5592,
	            'module_id' => 1,
	            'user_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 5593,
	            'module_id' => 1,
	            'user_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 5594,
	            'module_id' => 2,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5595,
	            'module_id' => 3,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5596,
	            'module_id' => 3,
	            'user_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 5597,
	            'module_id' => 3,
	            'user_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 5598,
	            'module_id' => 3,
	            'user_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 5599,
	            'module_id' => 6,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5600,
	            'module_id' => 6,
	            'user_id' => 1,
	            'activity_id' => 7
	        ],
	        [
	            'id' => 5601,
	            'module_id' => 6,
	            'user_id' => 1,
	            'activity_id' => 8
	        ],
	        [
	            'id' => 5602,
	            'module_id' => 6,
	            'user_id' => 1,
	            'activity_id' => 11
	        ],
	        [
	            'id' => 5603,
	            'module_id' => 7,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5604,
	            'module_id' => 7,
	            'user_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 5605,
	            'module_id' => 7,
	            'user_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 5606,
	            'module_id' => 7,
	            'user_id' => 1,
	            'activity_id' => 7
	        ],
	        [
	            'id' => 5607,
	            'module_id' => 8,
	            'user_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 5608,
	            'module_id' => 8,
	            'user_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 5609,
	            'module_id' => 8,
	            'user_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 5610,
	            'module_id' => 8,
	            'user_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 5611,
	            'module_id' => 9,
	            'user_id' => 1,
	            'activity_id' => 3
	        ]

	    ]);

    }
}
