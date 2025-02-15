<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedVisitorFormsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visitor_forms')->delete();
        
        \DB::table('visitor_forms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event_announcement_id' => 1,
                'fields' => '[{"label":{"fr":"nom complet","en":"full name","ar":"\\u0627\\u0644\\u0625\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"addresse email","en":"email address","ar":"\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0627\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"email","required":true}]',
                'created_at' => '2025-02-15 17:50:07',
                'updated_at' => '2025-02-15 18:01:14',
            ),
        ));
        
        
    }
}