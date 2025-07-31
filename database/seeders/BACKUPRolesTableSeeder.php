<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPRolesTableSeeder extends Seeder
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
                'id' => 5,
                'name' => 'hostess',
                'guard_name' => 'web',
                'created_at' => '2025-07-04 19:09:27',
                'updated_at' => '2025-07-04 19:09:27',
            ),
        ));
        
        
    }
}