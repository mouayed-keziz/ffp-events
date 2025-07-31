<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPEventAnnouncementUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_announcement_user')->delete();
        
        \DB::table('event_announcement_user')->insert(array (
            0 => 
            array (
                'id' => 3,
                'event_announcement_id' => 4,
                'user_id' => 9,
                'created_at' => '2025-07-27 10:07:33',
                'updated_at' => '2025-07-27 10:07:33',
            ),
        ));
        
        
    }
}