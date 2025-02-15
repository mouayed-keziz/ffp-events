<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'super_admin',
                'guard_name' => 'web',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-15 17:47:56',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-15 17:47:56',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'exhibitor',
                'guard_name' => 'web',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-15 17:47:56',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'visitor',
                'guard_name' => 'web',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-15 17:47:56',
            ),
        ));
        
        
    }
}