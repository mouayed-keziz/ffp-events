<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('articles')->delete();
        
        
        
    }
}