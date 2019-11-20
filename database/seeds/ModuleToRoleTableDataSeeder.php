<?php

use Illuminate\Database\Seeder;

class ModuleToRoleTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('moduleToRole')->insert([
        	[
	            'id' => 3888,
	            'module_id' => 1,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3889,
	            'module_id' => 1,
	            'role_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 3890,
	            'module_id' => 1,
	            'role_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 3891,
	            'module_id' => 1,
	            'role_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 3892,
	            'module_id' => 2,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3893,
	            'module_id' => 3,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3894,
	            'module_id' => 3,
	            'role_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 3895,
	            'module_id' => 3,
	            'role_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 3896,
	            'module_id' => 3,
	            'role_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 3897,
	            'module_id' => 6,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3898,
	            'module_id' => 6,
	            'role_id' => 1,
	            'activity_id' => 7
	        ],
	        [
	            'id' => 3899,
	            'module_id' => 6,
	            'role_id' => 1,
	            'activity_id' => 8
	        ],
	        [
	            'id' => 3900,
	            'module_id' => 6,
	            'role_id' => 1,
	            'activity_id' => 11
	        ],
	        [
	            'id' => 3901,
	            'module_id' => 7,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3902,
	            'module_id' => 7,
	            'role_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 3903,
	            'module_id' => 7,
	            'role_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 3904,
	            'module_id' => 7,
	            'role_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 3905,
	            'module_id' => 8,
	            'role_id' => 1,
	            'activity_id' => 1
	        ],
	        [
	            'id' => 3906,
	            'module_id' => 8,
	            'role_id' => 1,
	            'activity_id' => 2
	        ],
	        [
	            'id' => 3907,
	            'module_id' => 8,
	            'role_id' => 1,
	            'activity_id' => 3
	        ],
	        [
	            'id' => 3908,
	            'module_id' => 8,
	            'role_id' => 1,
	            'activity_id' => 4
	        ],
	        [
	            'id' => 3909,
	            'module_id' => 9,
	            'role_id' => 1,
	            'activity_id' => 3
	        ]

	    ]);
    }
}
