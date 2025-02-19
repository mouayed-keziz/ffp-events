<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedVisitorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visitors')->delete();
        
        \DB::table('visitors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'hello',
                'email' => 'hello@world.com',
                'verified_at' => NULL,
                'password' => '$2y$12$2bNe42SfZXnAxaIJPo0MDOLKmo2hlpD2x/ksj9.oVfzxxHlofOIDq',
                'remember_token' => NULL,
                'created_at' => '2025-02-17 21:44:07',
                'updated_at' => '2025-02-17 21:45:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}