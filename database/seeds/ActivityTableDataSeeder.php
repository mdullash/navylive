<?php

use Illuminate\Database\Seeder;

class ActivityTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity')->insert([
        	[
	            'id' => 1,
	            'name' => 'View',
	            'description' => 'View',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 2,
	            'name' => 'Create',
	            'description' => 'Create',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 3,
	            'name' => 'Update',
	            'description' => 'Update',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 4,
	            'name' => 'Delete',
	            'description' => 'Delete',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 5,
	            'name' => 'Lock',
	            'description' => 'Lock',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 6,
	            'name' => 'Download',
	            'description' => 'Download',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 7,
	            'name' => 'Change password',
	            'description' => 'Change password',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 8,
	            'name' => 'Password reset',
	            'description' => 'Password reset',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 9,
	            'name' => 'Print',
	            'description' => 'Print',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 10,
	            'name' => 'Commit',
	            'description' => 'Commit',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 11,
	            'name' => 'Activate',
	            'description' => 'Activate',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 12,
	            'name' => 'Approve',
	            'description' => 'Approve',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 13,
	            'name' => 'Decline',
	            'description' => 'Decline',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 14,
	            'name' => 'Amend',
	            'description' => 'Amend',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 15,
	            'name' => 'Details',
	            'description' => 'Details',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ]

	    ]);
    }
}
