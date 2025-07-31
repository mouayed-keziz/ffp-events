<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPBadgeCheckLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('badge_check_logs')->delete();
        
        \DB::table('badge_check_logs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event_announcement_id' => 4,
                'badge_id' => 97,
                'checked_by_user_id' => 9,
                'action' => 'check_in',
                'action_time' => '2025-07-27 10:08:51',
                'note' => NULL,
                'badge_code' => 'a5d9df03-d51a-40b4-a3f8-cd3340bc73d1',
                'badge_name' => 'tesat',
                'badge_email' => 'r_kouiderhacene@estin.dz',
                'badge_position' => 'Directeur Technique',
                'badge_company' => 'entre',
                'created_at' => '2025-07-27 10:08:51',
                'updated_at' => '2025-07-27 10:08:51',
            ),
            1 => 
            array (
                'id' => 2,
                'event_announcement_id' => 4,
                'badge_id' => 97,
                'checked_by_user_id' => 9,
                'action' => 'check_out',
                'action_time' => '2025-07-27 10:12:20',
                'note' => NULL,
                'badge_code' => 'a5d9df03-d51a-40b4-a3f8-cd3340bc73d1',
                'badge_name' => 'tesat',
                'badge_email' => 'r_kouiderhacene@estin.dz',
                'badge_position' => 'Directeur Technique',
                'badge_company' => 'entre',
                'created_at' => '2025-07-27 10:12:20',
                'updated_at' => '2025-07-27 10:12:20',
            ),
        ));
        
        
    }
}