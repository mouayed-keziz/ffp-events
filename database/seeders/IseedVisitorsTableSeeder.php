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
            1 => 
            array (
                'id' => 6,
                'name' => 'Mouayed Keziz',
                'email' => 'lmou@admin.dev',
                'verified_at' => NULL,
                'password' => '$2y$12$2GOpO66FOLccl6DLbiSHGe/s3RevpslTEtAIPFnlKG92JedeHUZdG',
                'remember_token' => NULL,
                'created_at' => '2025-03-09 22:17:36',
                'updated_at' => '2025-03-09 22:17:36',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}