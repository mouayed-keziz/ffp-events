<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedVisitorSubmissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visitor_submissions')->delete();
        
        \DB::table('visitor_submissions')->insert(array (
            0 => 
            array (
                'id' => 50,
                'visitor_id' => 5,
                'event_announcement_id' => 3,
                'answers' => '[{"title":{"fr":"dazd","en":"qsd","ar":"qsd"},"fields":[{"type":"input","data":{"label":{"fr":"azd","en":"qsd","ar":"qsd"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azd"}]}]',
                'status' => 'approved',
                'created_at' => '2025-03-09 23:27:54',
                'updated_at' => '2025-03-09 23:27:54',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}