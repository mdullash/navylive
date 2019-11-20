<?php

use Illuminate\Database\Seeder;

class ModuleTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('module')->insert([
         	[
	            'id' => 1,
	            'name' => 'Role Management',
	            'description' => 'Role Management',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 2,
	            'name' => 'Role Access Control',
	            'description' => 'Role Access Control',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 3,
	            'name' => 'User Management',
	            'description' => 'User Management',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 6,
	            'name' => 'User Access Control',
	            'description' => 'User Access Control',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 7,
	            'name' => 'Module Management',
	            'description' => 'Module Management',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 8,
	            'name' => 'Activity Management',
	            'description' => 'Activity Management',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ],
	        [
	            'id' => 9,
	            'name' => 'Settings',
	            'description' => 'Settings',
	            'created_by' => 0,
	            'updated_by' => 0,
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ]

	    ]);
    }
}
