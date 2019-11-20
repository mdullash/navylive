<?php

use Illuminate\Database\Seeder;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
	            'id' => 1,
	            'role_id' => 1,
	            'first_name' => 'system',
	            'last_name' => 'Administrator',
	            'email' => 'system@info.com',
	            'contact_no' => '019999999999',
	            'username' => 'admin',
	            'password' => '$2y$10$hVIomzxBfnDgPK944tYXLOb2YlaFb.EUh4MggdYl34DVQN9ZSuPPe',
	            'designation' => null,
	            'photo' => '5bbc9f59e09b5510222832-612x612.jpg',
	            'operator_id' => null,
	            'group_id' => null,
	            'recover_attempt' => null,
	            'recover_link' => null,
	            'status_id' => 1,
	            'remember_token' => 'LePIAooxHe615R4XNdGNel9lAHfHnrbatLWIcFHFIL7X0x5GYHQgwZCxlu5y',
	            'token' => null,
	            'lastLoginTime' => '2018-04-04 12:12:42',
	            'regMetaServer' => null,
	            'loginMetaServer' => '{"REDIRECT_STATUS":"200","HTTP_HOST":"quizbook.com.bd","HTTP_CONNECTION":"keep-alive","CONTENT_LENGTH":"88","HTTP_CACHE_CONTROL":"max-age=0","HTTP_ORIGIN":"http:\/\/quizbook.com.bd","HTTP_UPGRADE_INSECURE_REQUESTS":"1","CONTENT_TYPE":"application\/x-www-f',
	            'created_by' => '55',
	            'updated_by' => '55',
	            'created_at' => '2015-10-15 04:21:06',
	            'updated_at' => '2015-10-15 04:21:06'
	        ]);
    }
}
