<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedRoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_has_permissions')->delete();
        
        
        
    }
}