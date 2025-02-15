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
                'fields' => '[{"label":{"fr":"nom complet","en":"full name","ar":"\\u0627\\u0644\\u0625\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"addresse email","en":"email address","ar":"\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0627\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"email","required":true},{"label":{"fr":"Niveau d\'int\\u00e9r\\u00eat","en":"Level of Interest","ar":"\\u0645\\u0633\\u062a\\u0648\\u0649 \\u0627\\u0644\\u0627\\u0647\\u062a\\u0645\\u0627\\u0645"},"description":{"fr":null,"en":null,"ar":null},"type":"single_option","required":true,"options":[{"value":{"fr":"Faible","en":"Low","ar":"\\u0636\\u0639\\u064a\\u0641"}},{"value":{"fr":"Moyen","en":"Medium","ar":"\\u0645\\u062a\\u0648\\u0633\\u0637"}},{"value":{"fr":"\\u00c9lev\\u00e9","en":"High ","ar":"\\u0639\\u0627\\u0644\\u064a"}},{"value":{"fr":"Tr\\u00e8s \\u00e9lev\\u00e9","en":"Very High ","ar":"\\u0639\\u0627\\u0644\\u064d \\u062c\\u062f\\u0627"}}]},{"label":{"fr":"Nom","en":"Name","ar":"\\u0627\\u0644\\u0625\\u0633\\u0645"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"Pr\\u00e9nom","en":"First Name","ar":"\\u0627\\u0644\\u0623\\u0633\\u0645 \\u0627\\u0644\\u0623\\u0648\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"T\\u00e9l\\u00e9phone","en":"Phone","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true},{"label":{"fr":"Mobile","en":"Mobile Phone","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u062c\\u0648\\u0627\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true},{"label":{"fr":"Fonction","en":"Function","ar":"\\u0648\\u0638\\u064a\\u0641\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"Pays","en":"Country","ar":"\\u0627\\u0644\\u062f\\u0648\\u0644\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},{"label":{"fr":"Wilaya","en":"Province","ar":"\\u0627\\u0644\\u0648\\u0644\\u0627\\u064a\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}]',
                'created_at' => '2025-02-15 17:50:07',
                'updated_at' => '2025-02-15 18:13:18',
            ),
        ));
        
        
    }
}