<?php

use Illuminate\Database\Seeder;

class SettingsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
	            'id' => 1,
	            'site_title' => 'Mobishop WAP Portal',
	            'tag_line' => 'Mobishop',
	            'site_description' => 'Mobishop',
	            'email' => 'mobishop@gmail.com',
	            'phone' => '09614151617',
	            'location' => 'House 71, Road 7, Sector 4, Uttara Dhaka, Bangladesh 1230',
	            'logo' => 'public/upload/systemSettings/7Q6TW0cmMJgAjUkTtxlN.png',
	            'favicon' => 'public/upload/systemSettings/fy2vBT64LzWODLKvTlpH.ico',
	            'copyRight' => 'Mobishop. All Rights Reserved',
	            'created_by' => '55',
	            'updated_by' => '55',
	            'created_at' => '2018-02-08 00:00:00',
	            'updated_at' => '2018-10-14 09:23:44'
	        ]);
    }
}

