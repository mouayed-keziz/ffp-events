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
                'sections' => '[{"title":{"fr":"Informations personnelles","en":"Informations personnelles","ar":"Informations personnelles"},"fields":[{"type":"input","data":{"label":{"fr":"Wilaya","en":"Wilaya","ar":"Wilaya"},"description":{"fr":null,"en":null,"ar":null},"type":"number","required":true}},{"type":"input","data":{"label":{"fr":"mouayed","en":"lmou","ar":"\\u0645\\u0624\\u064a\\u062f"},"description":{"fr":null,"en":null,"ar":null},"type":"paragraph","required":false}},{"type":"input","data":{"label":{"fr":"email","en":"email","ar":"email"},"description":{"fr":null,"en":null,"ar":null},"type":"email","required":false}},{"type":"input","data":{"label":{"fr":"Nom","en":"Nom","ar":"\\u0627\\u0644\\u0625\\u0633\\u0645"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pr\\u00e9nom","en":"Pr\\u00e9nom","ar":"Pr\\u00e9nom"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"T\\u00e9l\\u00e9phone","en":"T\\u00e9l\\u00e9phone","ar":"T\\u00e9l\\u00e9phone"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Mobile","en":"Mobile","ar":"Mobile"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Fonction","en":"Fonction","ar":"Fonction"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pays","en":"Pays","ar":"Pays"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]},{"title":{"fr":"Int\\u00e9r\\u00eat","en":"Int\\u00e9r\\u00eat","ar":"\\u0641\\u0627\\u064a\\u062f\\u0629"},"fields":[{"type":"select","data":{"label":{"fr":"Niveau d\'int\\u00e9r\\u00eat","en":"Niveau d\'int\\u00e9r\\u00eat","ar":"Niveau d\'int\\u00e9r\\u00eat"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"Faible","en":"Faible","ar":"Faible"}},{"option":{"fr":"Moyen","en":"Moyen","ar":"Moyen"}},{"option":{"fr":"\\u00c9lev\\u00e9","en":"\\u00c9lev\\u00e9","ar":"\\u00c9lev\\u00e9"}},{"option":{"fr":"Tr\\u00e8s \\u00e9lev\\u00e9","en":"Tr\\u00e8s \\u00e9lev\\u00e9","ar":"Tr\\u00e8s \\u00e9lev\\u00e9"}}]}},{"type":"select","data":{"label":{"fr":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","en":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*","ar":"Etes-vous la personne qui prend la d\\u00e9cision d\\u2019exposer*"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"option 1","en":"option 1","ar":"option 1"}},{"option":{"fr":"option 2","en":"option 2","ar":"option 2"}},{"option":{"fr":"option 3","en":"option 3","ar":"option 3"}}]}}]},{"title":{"fr":"Informations sur l\\u2019entreprise","en":"Informations sur l\\u2019entreprise","ar":"Informations sur l\\u2019entreprise"},"fields":[{"type":"upload","data":{"label":{"fr":"aze","en":"aze","ar":"hehe"},"description":{"fr":null,"en":null,"ar":null},"file_type":"image"}},{"type":"input","data":{"label":{"fr":"Entreprise","en":"Entreprise","ar":"Entreprise"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Secteur d\\u2019activit\\u00e9","en":"Secteur d\\u2019activit\\u00e9","ar":"Secteur d\\u2019activit\\u00e9"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Site web","en":"Site web","ar":"Site web"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"checkbox","data":{"label":{"fr":"skills","en":"skills","ar":"skills"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"c","en":"c","ar":"c"}},{"option":{"fr":"python","en":"python","ar":"python"}},{"option":{"fr":"java","en":"java","ar":"java"}},{"option":{"fr":"javascript","en":"javascript","ar":"javascript"}},{"option":{"fr":"php","en":"php","ar":"php"}}]}},{"type":"radio","data":{"label":{"fr":"age","en":"age","ar":"age"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"18 - 23","en":"18 - 23","ar":"18 - 23"}},{"option":{"fr":"24 - 35","en":"24 - 35","ar":"24 - 35"}},{"option":{"fr":"+35","en":"+35","ar":"+35"}}]}}]}]',
                'created_at' => '2025-02-15 17:50:07',
                'updated_at' => '2025-02-27 09:40:06',
            ),
            1 => 
            array (
                'id' => 2,
                'event_announcement_id' => 2,
                'sections' => '[{"title":{"fr":"informations general","en":"general information","ar":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0627\\u0645\\u0629"},"fields":[{"type":"input","data":{"label":{"fr":"nom complet","en":"full name","ar":"\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"select","data":{"label":{"fr":"genre","en":"gender","ar":"\\u0627\\u0644\\u062c\\u0646\\u0633"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"male","en":"male","ar":"\\u0630\\u0643\\u0631"}},{"option":{"fr":"female","en":"female","ar":"\\u0627\\u0646\\u062b\\u0649"}}]}},{"type":"checkbox","data":{"label":{"fr":"tu est ?","en":"you are a ?","ar":"\\u0627\\u0646\\u062a :"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"etudiant","en":"student","ar":"\\u0637\\u0627\\u0644\\u0628"}},{"option":{"fr":"employ\\u00e9","en":"employe","ar":"\\u0645\\u0648\\u0638\\u0641"}},{"option":{"fr":"entrepreneur","en":"entrepreneur","ar":"\\u0631\\u062c\\u0644 \\u0627\\u0639\\u0645\\u0627\\u0644"}}]}},{"type":"radio","data":{"label":{"fr":"Tranche d\'\\u00e2ge","en":"Age group","ar":"\\u0627\\u0644\\u0641\\u0626\\u0629 \\u0627\\u0644\\u0639\\u0645\\u0631\\u064a\\u0629"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"18-25","en":"18-25","ar":"18-25"}},{"option":{"fr":"25-32","en":"25-32","ar":"25-32"}},{"option":{"fr":"+32","en":"+32","ar":"+32"}}]}},{"type":"upload","data":{"label":{"fr":"CV","en":"CV","ar":"CV"},"description":{"fr":null,"en":null,"ar":null},"file_type":"image"}}]},{"title":{"fr":"nv","en":"new","ar":"\\u062c\\u062f\\u064a\\u062f"},"fields":[{"type":"input","data":{"label":{"fr":"ton nom","en":"your name","ar":"\\u0627\\u0633\\u0645\\u0643"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]},{"title":{"fr":"nv2","en":"new2","ar":"\\u062c\\u062f\\u064a\\u062f2"},"fields":[{"type":"select","data":{"label":{"fr":"cache","en":"cache","ar":"cache"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"1","en":"11","ar":"111"}},{"option":{"fr":"2","en":"22","ar":"222"}}]}}]}]',
                'created_at' => '2025-02-15 20:41:16',
                'updated_at' => '2025-03-07 22:38:44',
            ),
            2 => 
            array (
                'id' => 3,
                'event_announcement_id' => 3,
                'sections' => '[{"title":{"fr":"dazd","en":"qsd","ar":"qsd"},"fields":[{"type":"input","data":{"label":{"fr":"azd","en":"qsd","ar":"qsd"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}}]}]',
                'created_at' => '2025-03-07 23:10:11',
                'updated_at' => '2025-03-07 23:11:24',
            ),
        ));
        
        
    }
}