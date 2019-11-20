<?php

use Illuminate\Database\Seeder;

class RoleTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
        	[
	            'id' => 1,
	            'name' => 'Super Admin',
	            'info' => 'Super User of this application who can manage all kind of operation',
	            'priority' => 1,
	            'status_id' => 1,
	            'created_by' => '55',
	            'updated_by' => '55',
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 2,
	            'name' => 'Administrator',
	            'info' => 'Limited access with almost all the features',
	            'priority' => 2,
	            'status_id' => 1,
	            'created_by' => '55',
	            'updated_by' => '55',
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 3,
	            'name' => 'Manager',
	            'info' => 'Manager',
	            'priority' => 3,
	            'status_id' => 1,
	            'created_by' => 1,
	            'updated_by' => 1,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 4,
	            'name' => 'Content Manager',
	            'info' => 'Content Manager',
	            'priority' => 4,
	            'status_id' => 1,
	            'created_by' => 1,
	            'updated_by' => 1,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 5,
	            'name' => 'Content Writer',
	            'info' => 'Content Writer',
	            'priority' => 5,
	            'status_id' => 1,
	            'created_by' => 1,
	            'updated_by' => 1,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 6,
	            'name' => 'Tele Operator',
	            'info' => 'Tele Operator',
	            'priority' => 6,
	            'status_id' => 1,
	            'created_by' => 1,
	            'updated_by' => 1,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 7,
	            'name' => 'Promoter',
	            'info' => 'Promoter',
	            'priority' => 7,
	            'status_id' => 1,
	            'created_by' => 1,
	            'updated_by' => 1,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ]

	    ]);
    }
}
