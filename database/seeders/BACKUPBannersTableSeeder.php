<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPBannersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('banners')->delete();
        
        \DB::table('banners')->insert(array (
            0 => 
            array (
                'id' => 2,
                'title' => 'test',
                'url' => NULL,
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2025-04-09 02:33:58',
                'updated_at' => '2025-07-11 01:43:41',
            ),
            1 => 
            array (
                'id' => 3,
                'title' => 'AMAZIT',
                'url' => NULL,
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-08 13:34:54',
                'updated_at' => '2025-07-11 01:43:40',
            ),
            2 => 
            array (
                'id' => 4,
                'title' => 'TPR',
                'url' => NULL,
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2025-06-17 15:37:56',
                'updated_at' => '2025-07-11 01:43:41',
            ),
            3 => 
            array (
                'id' => 5,
                'title' => 'ROTO',
                'url' => NULL,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-06-25 13:18:51',
                'updated_at' => '2025-07-11 01:44:35',
            ),
        ));
        
        
    }
}