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
                'name' => 'keziz mouayed',
                'email' => 'm_keziz@estin.dz',
                'verified_at' => NULL,
                'password' => 'hello world',
                'remember_token' => NULL,
                'currency' => 'DA',
                'created_at' => '2025-02-17 21:44:25',
                'updated_at' => '2025-02-17 21:44:25',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}