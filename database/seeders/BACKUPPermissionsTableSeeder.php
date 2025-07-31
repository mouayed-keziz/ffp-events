<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        
        
    }
}