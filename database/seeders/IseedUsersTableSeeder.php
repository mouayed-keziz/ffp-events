<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.dev',
                'verified_at' => '2025-02-15 17:47:56',
                'password' => '$2y$12$IFns2edeKLpiQCWNuz1SUe0Kddw/cPD23SedmWs4e1vr09LZbQdd2',
                'remember_token' => 'DdcEAw9ZzG',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-15 17:47:56',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}