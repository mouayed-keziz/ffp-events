<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPArticleCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('article_category')->delete();
        
        
        
    }
}