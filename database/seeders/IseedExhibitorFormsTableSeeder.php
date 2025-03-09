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
                'sections' => '[{"title":{"fr":"Int\\u00e9r\\u00eat","en":"Int\\u00e9r\\u00eat","ar":"\\u0641\\u0627\\u064a\\u062f\\u0629"},"fields":[{"type":"select","data":{"label":{"fr":"Niveau d\'int\\u00e9r\\u00eat","en":"Niveau d\'int\\u00e9r\\u00eat","ar":"Niveau d\'int\\u00e9r\\u00eat"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"Faible","en":"Faible","ar":"Faible"}},{"option":{"fr":"Moyen","en":"Moyen","ar":"Moyen"}},{"option":{"fr":"\\u00c9lev\\u00e9","en":"\\u00c9lev\\u00e9","ar":"\\u00c9lev\\u00e9"}},{"option":{"fr":"Tr\\u00e8s \\u00e9lev\\u00e9","en":"Tr\\u00e8s \\u00e9lev\\u00e9","ar":"Tr\\u00e8s \\u00e9lev\\u00e9"}}]}},{"type":"select","data":{"label":{"fr":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","en":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","ar":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"option 1","en":"option 1","ar":"option 1"}},{"option":{"fr":"option 2","en":"option 2","ar":"option 2"}},{"option":{"fr":"option 3","en":"option 3","ar":"option 3"}}]}},{"type":"ecommerce","data":{"label":{"fr":"salut le monde","en":"hello world","ar":"\\u0645\\u0631\\u062d\\u0628\\u0627 \\u0627\\u064a\\u0647\\u0627 \\u0627\\u0644\\u0639\\u0627\\u0644\\u0645"},"description":{"fr":null,"en":null,"ar":null},"products":[{"product_id":"1","price":{"EUR":"2000","USD":"3000","DZD":"5000"}}]}},{"type":"plan_tier","data":{"label":{"fr":"keziz","en":"keziz","ar":"keziz"},"description":{"fr":null,"en":null,"ar":null},"plan_tier_id":"1"}}]},{"title":{"fr":"Informations personnelles","en":"Informations personnelles","ar":"Informations personnelles"},"fields":[{"type":"input","data":{"label":{"fr":"Nom","en":"Nom","ar":"Nom"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pr\\u00e9nom","en":"Pr\\u00e9nom","ar":"Pr\\u00e9nom"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"T\\u00e9l\\u00e9phone","en":"T\\u00e9l\\u00e9phone","ar":"T\\u00e9l\\u00e9phone"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Mobile","en":"Mobile","ar":"Mobile"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Fonction","en":"Fonction","ar":"Fonction"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pays","en":"Pays","ar":"Pays"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Wilaya","en":"Wilaya","ar":"Wilaya"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]},{"title":{"fr":"Informations sur l\\u2019entreprise","en":"Informations sur l\\u2019entreprise","ar":"Informations sur l\\u2019entreprise"},"fields":[{"type":"input","data":{"label":{"fr":"Entreprise","en":"Entreprise","ar":"Entreprise"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Secteur d\\u2019activit\\u00e9","en":"Secteur d\\u2019activit\\u00e9","ar":"Secteur d\\u2019activit\\u00e9"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Site web","en":"Site web","ar":"Site web"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:37:13',
                'updated_at' => '2025-02-28 02:08:05',
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
                'sections' => '[{"title":{"fr":"azd","en":"qsd","ar":"qsd"},"fields":[{"type":"input","data":{"label":{"fr":"azd","en":"qsd","ar":"qsd"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":false}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:39:08',
                'updated_at' => '2025-03-07 21:56:01',
            ),
            3 => 
            array (
                'id' => 6,
                'title' => '{"fr":"Aménagement des stands et conception 3D","en":"Aménagement des stands et conception 3D","ar":"Aménagement des stands et conception 3D"}',
                'description' => '{"fr":"qsd","en":"qsd","ar":"qsd"}',
                'sections' => '[{"title":{"fr":"azd","en":"qsd","ar":"qsd"},"fields":[{"type":"ecommerce","data":{"label":{"fr":"azd","en":"qsd","ar":"qsd"},"description":{"fr":null,"en":null,"ar":null},"products":[{"product_id":"2","price":{"DZD":"50","EUR":"500","USD":"5000"}},{"product_id":"3","price":{"DZD":"55","EUR":"550","USD":"5500"}}]}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-02-19 12:39:23',
                'updated_at' => '2025-03-07 21:56:39',
            ),
            4 => 
            array (
                'id' => 7,
                'title' => '{"fr":"premier","en":"first","ar":"اول"}',
                'description' => '{"fr":"premier","en":"first","ar":"اول"}',
                'sections' => '[{"title":{"fr":"keziz mouayed","en":"keziz mouayed","ar":"keziz mouayed"},"fields":[{"type":"select_priced","data":{"label":{"fr":"lmou","en":"lmou","ar":"lmou"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"option 1","en":"option 1","ar":"option 1"},"price":{"DZD":"10","EUR":"100","USD":"1000"}},{"option":{"fr":"option 2","en":"option 2","ar":"option 2"},"price":{"DZD":"20","EUR":"200","USD":"2000"}},{"option":{"fr":"option 3","en":"option 3","ar":"option 3"},"price":{"DZD":"30","EUR":"300","USD":"3000"}}]}},{"type":"checkbox_priced","data":{"label":{"fr":"qqqqqq","en":"qqqqqq","ar":"qqqqq"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"b","en":"b","ar":"b"},"price":{"DZD":"40","EUR":"400","USD":"4000"}},{"option":{"fr":"a","en":"a","ar":"a"},"price":{"DZD":"50","EUR":"500","USD":"5000"}}]}},{"type":"radio_priced","data":{"label":{"fr":"wwwwwwww","en":"wwwwwwww","ar":"wwwwwwww"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"c","en":"c","ar":"c"},"price":{"DZD":"60","EUR":"600","USD":"6000"}},{"option":{"fr":"d","en":"d","ar":"d"},"price":{"DZD":"70","EUR":"700","USD":"7000"}}]}},{"type":"input","data":{"label":{"fr":"hello","en":"hello","ar":"hello"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"checkbox","data":{"label":{"fr":"a","en":"b","ar":"c"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"1","en":"1","ar":"1"}},{"option":{"fr":"2","en":"2","ar":"2"}}]}},{"type":"radio","data":{"label":{"fr":"azd","en":"qsd","ar":"wxc"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"11","en":"11","ar":"11"}},{"option":{"fr":"22","en":"22","ar":"22"}}]}},{"type":"upload","data":{"label":{"fr":"file","en":"file","ar":"file"},"description":{"fr":null,"en":null,"ar":null},"file_type":"pdf"}},{"type":"select","data":{"label":{"fr":"q","en":"q","ar":"q"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"111","en":"111","ar":"111"}},{"option":{"fr":"222","en":"222","ar":"222"}},{"option":{"fr":"333","en":"333","ar":"333"}}]}},{"type":"ecommerce","data":{"label":{"fr":"meible","en":"meible","ar":"memible"},"description":{"fr":null,"en":null,"ar":null},"products":[{"product_id":"1","price":{"DZD":"10","EUR":"100","USD":"1000"}},{"product_id":"2","price":{"DZD":"20","EUR":"200","USD":"2000"}},{"product_id":"3","price":{"DZD":"30","EUR":"300","USD":"3000"}},{"product_id":"4","price":{"DZD":"40","EUR":"400","USD":"4000"}}]}},{"type":"plan_tier","data":{"label":{"fr":"azd","en":"qsd","ar":"wxc"},"description":{"fr":null,"en":null,"ar":null},"plan_tier_id":"1"}}]}]',
                'event_announcement_id' => 2,
                'created_at' => '2025-02-28 16:36:11',
                'updated_at' => '2025-03-06 15:20:59',
            ),
            5 => 
            array (
                'id' => 9,
                'title' => '{"fr":"deuxieme","en":"deuxieme","ar":"deuxieme"}',
                'description' => '{"ar":null}',
                'sections' => '[{"title":{"fr":"hello world","en":"hello world","ar":"hello world"},"fields":[{"type":"plan_tier","data":{"label":{"fr":"azd","en":"qsd","ar":"wxc"},"description":{"fr":null,"en":null,"ar":null},"plan_tier_id":"1"}},{"type":"ecommerce","data":{"label":{"fr":"qsc","en":"qsc","ar":"qsc"},"description":{"fr":null,"en":null,"ar":null},"products":[{"product_id":"2","price":{"DZD":"80","EUR":"800","USD":"8000"}},{"product_id":"1","price":{"DZD":"90","EUR":"900","USD":"9000"}}]}}]}]',
                'event_announcement_id' => 2,
                'created_at' => '2025-03-07 20:02:53',
                'updated_at' => '2025-03-07 20:02:53',
            ),
            6 => 
            array (
                'id' => 10,
                'title' => '{"fr":"ad","en":"db","ar":"sd"}',
                'description' => '{"fr":"sd","en":"qsd","ar":"qsd"}',
                'sections' => '[{"title":{"fr":"azd","en":"qsd","ar":"wxc"},"fields":[]}]',
                'event_announcement_id' => 3,
                'created_at' => '2025-03-07 23:56:11',
                'updated_at' => '2025-03-07 23:56:11',
            ),
            7 => 
            array (
                'id' => 11,
                'title' => '{"fr":"azd","en":"qsd","ar":"wxc"}',
                'description' => '{"fr":"wxc","en":"qsd","ar":"azd"}',
                'sections' => '[{"title":{"fr":"azd","en":"qsd","ar":"wxc"},"fields":[]}]',
                'event_announcement_id' => 3,
                'created_at' => '2025-03-07 23:56:36',
                'updated_at' => '2025-03-07 23:56:36',
            ),
        ));
        
        
    }
}