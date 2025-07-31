<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        
        
    }
}