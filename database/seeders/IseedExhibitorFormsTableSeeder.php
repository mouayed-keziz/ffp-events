<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedExhibitorFormsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exhibitor_forms')->delete();
        
        \DB::table('exhibitor_forms')->insert(array (
            0 => 
            array (
                'id' => 3,
                'title' => '{"fr":"information generate","en":"general informations","ar":"معلومات عامة"}',
                'description' => '{"ar":null}',
                'sections' => '[{"title":{"fr":"Int\\u00e9r\\u00eat","en":"Int\\u00e9r\\u00eat","ar":"Int\\u00e9r\\u00eat"},"fields":[{"type":"select","data":{"label":{"fr":"Niveau d\'int\\u00e9r\\u00eat","en":"Niveau d\'int\\u00e9r\\u00eat","ar":"Niveau d\'int\\u00e9r\\u00eat"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"Faible","en":"Faible","ar":"Faible"}},{"option":{"fr":"Moyen","en":"Moyen","ar":"Moyen"}},{"option":{"fr":"\\u00c9lev\\u00e9","en":"\\u00c9lev\\u00e9","ar":"\\u00c9lev\\u00e9"}},{"option":{"fr":"Tr\\u00e8s \\u00e9lev\\u00e9","en":"Tr\\u00e8s \\u00e9lev\\u00e9","ar":"Tr\\u00e8s \\u00e9lev\\u00e9"}}]}},{"type":"select","data":{"label":{"fr":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","en":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","ar":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"option 1","en":"option 1","ar":"option 1"}},{"option":{"fr":"option 2","en":"option 2","ar":"option 2"}},{"option":{"fr":"option 3","en":"option 3","ar":"option 3"}}]}}]},{"title":{"fr":"Informations personnelles","en":"Informations personnelles","ar":"Informations personnelles"},"fields":[{"type":"input","data":{"label":{"fr":"Nom","en":"Nom","ar":"Nom"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pr\\u00e9nom","en":"Pr\\u00e9nom","ar":"Pr\\u00e9nom"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"T\\u00e9l\\u00e9phone","en":"T\\u00e9l\\u00e9phone","ar":"T\\u00e9l\\u00e9phone"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Mobile","en":"Mobile","ar":"Mobile"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Fonction","en":"Fonction","ar":"Fonction"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pays","en":"Pays","ar":"Pays"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Wilaya","en":"Wilaya","ar":"Wilaya"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]},{"title":{"fr":"Informations sur l\\u2019entreprise","en":"Informations sur l\\u2019entreprise","ar":"Informations sur l\\u2019entreprise"},"fields":[{"type":"input","data":{"label":{"fr":"Entreprise","en":"Entreprise","ar":"Entreprise"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Secteur d\\u2019activit\\u00e9","en":"Secteur d\\u2019activit\\u00e9","ar":"Secteur d\\u2019activit\\u00e9"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Site web","en":"Site web","ar":"Site web"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:37:13',
                'updated_at' => '2025-02-19 12:44:27',
            ),
            1 => 
            array (
                'id' => 4,
                'title' => '{"fr":"Réservation d’espaces","en":"Réservation d’espaces","ar":"Réservation d’espaces"}',
                'description' => '{"ar":null}',
            'sections' => '[{"title":{"fr":"Choisissez votre \\u00e9space:","en":"Choisissez votre \\u00e9space:","ar":"Choisissez votre \\u00e9space:"},"fields":[{"type":"select_priced","data":{"label":{"fr":"Choix du stand","en":"Choix du stand","ar":"Choix du stand"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"A1 (3 fa\\u00e7ades x 20m\\u00b2)","en":"A1 (3 fa\\u00e7ades x 20m\\u00b2)","ar":"A1 (3 fa\\u00e7ades x 20m\\u00b2)"},"price":{"EUR":"110","USD":"130","DZD":"25000"}},{"option":{"fr":"A2 (3 fa\\u00e7ades x 15m\\u00b2)","en":"A2 (3 fa\\u00e7ades x 15m\\u00b2)","ar":"A2 (3 fa\\u00e7ades x 15m\\u00b2)"},"price":{"EUR":"100","USD":"90","DZD":"20000"}},{"option":{"fr":"A3 (1 fa\\u00e7ades x 10m\\u00b2)","en":"A3 (1 fa\\u00e7ades x 10m\\u00b2)","ar":"A3 (1 fa\\u00e7ades x 10m\\u00b2)"},"price":{"EUR":"90","USD":"80","DZD":"15000"}}]}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:38:16',
                'updated_at' => '2025-02-19 12:47:02',
            ),
            2 => 
            array (
                'id' => 5,
                'title' => '{"fr":"Sponsoring","en":"Sponsoring","ar":"Sponsoring"}',
                'description' => '{"ar":null}',
                'sections' => '[]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:39:08',
                'updated_at' => '2025-02-19 12:39:08',
            ),
            3 => 
            array (
                'id' => 6,
                'title' => '{"fr":"Aménagement des stands et conception 3D","en":"Aménagement des stands et conception 3D","ar":"Aménagement des stands et conception 3D"}',
                'description' => '{"ar":null}',
                'sections' => '[]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:39:23',
                'updated_at' => '2025-02-19 12:39:23',
            ),
        ));
        
        
    }
}