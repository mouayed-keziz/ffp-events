<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedExhibitorSubmissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exhibitor_submissions')->delete();
        
        
        
    }
}