<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedVisitorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visitors')->delete();
        
        \DB::table('visitors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'hello',
                'email' => 'hello@world.com',
                'verified_at' => NULL,
                'password' => '$2y$12$KJ7Amjm6dwBK73cObRoluefxa0bUDHBfT2XbqQ.WYA6Z/LCOffe3q',
                'remember_token' => NULL,
                'created_at' => '2025-02-17 21:44:07',
                'updated_at' => '2025-02-22 02:25:37',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'lmou visitor',
                'email' => 'mouayed@admin.dev',
                'verified_at' => NULL,
                'password' => '$2y$12$ywvUw8f4keGVXDKRkPc0xuGunJecLhJkSlj0HaO2G1KjU.R/gSNRa',
                'remember_token' => NULL,
                'created_at' => '2025-02-22 02:35:46',
                'updated_at' => '2025-02-22 02:35:46',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'admin visitor',
                'email' => 'admin@admin.dev',
                'verified_at' => NULL,
                'password' => '$2y$12$Yezbbs5iN3azepsTzr1iauCVCZpkyVmMXQS5kD7yQdEcSNorujQpK',
                'remember_token' => NULL,
                'created_at' => '2025-02-22 02:36:44',
                'updated_at' => '2025-02-22 02:36:44',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'visitor1',
                'email' => 'visitor1@admin.dev',
                'verified_at' => NULL,
                'password' => '$2y$12$Xx3CAk0Ag9XDDItVfOlwMuSYuS/6dBl7kgcFdckxyUdmykB8n3WIG',
                'remember_token' => NULL,
                'created_at' => '2025-02-22 17:35:37',
                'updated_at' => '2025-02-22 17:35:37',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Mouayed Keziz',
                'email' => 'm_keziz@estin.dz',
                'verified_at' => NULL,
                'password' => '$2y$12$GvatV3dl0DD/vaoyWwdd9uNPinlu08cN5HbG6gqN/7JmVNz.nZKfW',
                'remember_token' => NULL,
                'created_at' => '2025-02-26 18:43:27',
                'updated_at' => '2025-02-26 18:43:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}