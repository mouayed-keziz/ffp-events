<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPLaravisitsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('laravisits')->delete();
        
        \DB::table('laravisits')->insert(array (
            0 => 
            array (
                'id' => 1,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"105.106.4.150"}',
                'created_at' => '2025-03-30 07:29:17',
                'updated_at' => '2025-03-30 07:29:17',
            ),
            1 => 
            array (
                'id' => 2,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"41.220.152.21"}',
                'created_at' => '2025-04-05 00:07:32',
                'updated_at' => '2025-04-05 00:07:32',
            ),
            2 => 
            array (
                'id' => 3,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"154.121.93.234"}',
                'created_at' => '2025-04-06 21:56:08',
                'updated_at' => '2025-04-06 21:56:08',
            ),
            3 => 
            array (
                'id' => 4,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"41.220.151.11"}',
                'created_at' => '2025-04-07 14:17:37',
                'updated_at' => '2025-04-07 14:17:37',
            ),
            4 => 
            array (
                'id' => 5,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"41.220.148.149"}',
                'created_at' => '2025-04-07 20:35:48',
                'updated_at' => '2025-04-07 20:35:48',
            ),
            5 => 
            array (
                'id' => 6,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 1,
                'data' => '{"ip":"105.101.97.18"}',
                'created_at' => '2025-04-08 13:02:26',
                'updated_at' => '2025-04-08 13:02:26',
            ),
            6 => 
            array (
                'id' => 7,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 2,
                'data' => '{"ip":"129.45.21.154"}',
                'created_at' => '2025-04-13 13:08:33',
                'updated_at' => '2025-04-13 13:08:33',
            ),
            7 => 
            array (
                'id' => 8,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 2,
                'data' => '{"ip":"185.189.113.124"}',
                'created_at' => '2025-04-22 23:02:25',
                'updated_at' => '2025-04-22 23:02:25',
            ),
            8 => 
            array (
                'id' => 9,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 3,
                'data' => '{"ip":"105.100.10.76"}',
                'created_at' => '2025-04-23 13:47:00',
                'updated_at' => '2025-04-23 13:47:00',
            ),
            9 => 
            array (
                'id' => 10,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 2,
                'data' => '{"ip":"105.100.10.76"}',
                'created_at' => '2025-04-23 13:47:13',
                'updated_at' => '2025-04-23 13:47:13',
            ),
            10 => 
            array (
                'id' => 11,
                'visitable_type' => 'App\\Models\\Article',
                'visitable_id' => 2,
                'data' => '{"ip":"129.45.36.155"}',
                'created_at' => '2025-04-25 19:31:08',
                'updated_at' => '2025-04-25 19:31:08',
            ),
        ));
        
        
    }
}