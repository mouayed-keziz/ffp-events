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
                'updated_at' => '2025-02-22 17:28:31',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}