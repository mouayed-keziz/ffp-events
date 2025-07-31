<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPImportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('imports')->delete();
        
        
        
    }
}