<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPSharesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shares')->delete();
        
        \DB::table('shares')->insert(array (
            0 => 
            array (
                'id' => 1,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'linkedin',
                'created_at' => '2025-04-05 00:07:40',
                'updated_at' => '2025-04-05 00:07:40',
            ),
            1 => 
            array (
                'id' => 2,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'facebook',
                'created_at' => '2025-04-05 00:07:48',
                'updated_at' => '2025-04-05 00:07:48',
            ),
            2 => 
            array (
                'id' => 3,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'instagram',
                'created_at' => '2025-04-05 00:07:56',
                'updated_at' => '2025-04-05 00:07:56',
            ),
            3 => 
            array (
                'id' => 4,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'instagram',
                'created_at' => '2025-04-05 00:08:41',
                'updated_at' => '2025-04-05 00:08:41',
            ),
            4 => 
            array (
                'id' => 5,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'facebook',
                'created_at' => '2025-04-05 00:08:50',
                'updated_at' => '2025-04-05 00:08:50',
            ),
            5 => 
            array (
                'id' => 6,
                'shareable_type' => 'App\\Models\\Article',
                'shareable_id' => 1,
                'platform' => 'linkedin',
                'created_at' => '2025-04-05 00:08:58',
                'updated_at' => '2025-04-05 00:08:58',
            ),
            6 => 
            array (
                'id' => 7,
                'shareable_type' => 'App\\Models\\EventAnnouncement',
                'shareable_id' => 1,
                'platform' => 'twitter',
                'created_at' => '2025-04-07 14:16:43',
                'updated_at' => '2025-04-07 14:16:43',
            ),
            7 => 
            array (
                'id' => 8,
                'shareable_type' => 'App\\Models\\EventAnnouncement',
                'shareable_id' => 4,
                'platform' => 'twitter',
                'created_at' => '2025-07-30 15:51:58',
                'updated_at' => '2025-07-30 15:51:58',
            ),
        ));
        
        
    }
}