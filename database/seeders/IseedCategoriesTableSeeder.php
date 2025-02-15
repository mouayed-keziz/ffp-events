<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedCategoriesTableSeeder extends Seeder
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