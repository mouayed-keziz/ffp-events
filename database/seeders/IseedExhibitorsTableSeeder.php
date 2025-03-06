<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedExhibitorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exhibitors')->delete();
        
        \DB::table('exhibitors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'mouayed keziz',
                'email' => 'm_keziz@estin.dz',
                'verified_at' => NULL,
                'password' => '$2y$12$qRxeAJKxTZhkQCJTjtd36.xWRPWgmkLwFD2JG8VyyaM9MJ0ZCy/1C',
                'remember_token' => NULL,
                'currency' => 'DZD',
                'created_at' => '2025-02-21 17:18:15',
                'updated_at' => '2025-03-06 16:07:21',
                'deleted_at' => '2025-03-06 16:07:21',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'redspot',
                'email' => 'redspot@yopmail.com',
                'verified_at' => NULL,
                'password' => '$2y$12$vTp7WGz/9ccLOjskOL48FulEd/0JTn8cOOmA7f9NlxvddDiIi5Mtu',
                'remember_token' => NULL,
                'currency' => 'DZD',
                'created_at' => '2025-03-06 16:07:37',
                'updated_at' => '2025-03-06 16:07:37',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}