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
                'id' => 34,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"informations general","en":"general information","ar":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0627\\u0645\\u0629"},"fields":[{"type":"input","data":{"label":{"fr":"nom complet","en":"full name","ar":"\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azd"},{"type":"select","data":{"label":{"fr":"genre","en":"gender","ar":"\\u0627\\u0644\\u062c\\u0646\\u0633"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"male","en":"male","ar":"\\u0630\\u0643\\u0631"}},{"option":{"fr":"female","en":"female","ar":"\\u0627\\u0646\\u062b\\u0649"}}]},"answer":{"ar":""}},{"type":"checkbox","data":{"label":{"fr":"tu est ?","en":"you are a ?","ar":"\\u0627\\u0646\\u062a :"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"etudiant","en":"student","ar":"\\u0637\\u0627\\u0644\\u0628"}},{"option":{"fr":"employ\\u00e9","en":"employe","ar":"\\u0645\\u0648\\u0638\\u0641"}},{"option":{"fr":"entrepreneur","en":"entrepreneur","ar":"\\u0631\\u062c\\u0644 \\u0627\\u0639\\u0645\\u0627\\u0644"}}]},"answer":[{"fr":"etudiant","en":"student","ar":"\\u0637\\u0627\\u0644\\u0628"},{"fr":"employ\\u00e9","en":"employe","ar":"\\u0645\\u0648\\u0638\\u0641"}]},{"type":"radio","data":{"label":{"fr":"Tranche d\'\\u00e2ge","en":"Age group","ar":"\\u0627\\u0644\\u0641\\u0626\\u0629 \\u0627\\u0644\\u0639\\u0645\\u0631\\u064a\\u0629"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"18-25","en":"18-25","ar":"18-25"}},{"option":{"fr":"25-32","en":"25-32","ar":"25-32"}},{"option":{"fr":"+32","en":"+32","ar":"+32"}}]},"answer":{"fr":"18-25","en":"18-25","ar":"18-25"}},{"type":"upload","data":{"label":{"fr":"CV","en":"CV","ar":"CV"},"description":{"fr":null,"en":null,"ar":null},"file_type":"pdf"},"answer":"0404ecff-d61f-4151-b3b1-a0fb9967f347"}]}]',
                'status' => 'approved',
                'created_at' => '2025-03-06 15:11:19',
                'updated_at' => '2025-03-06 15:11:19',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 35,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"informations general","en":"general information","ar":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0627\\u0645\\u0629"},"fields":[{"type":"input","data":{"label":{"fr":"nom complet","en":"full name","ar":"\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azdazd"},{"type":"select","data":{"label":{"fr":"genre","en":"gender","ar":"\\u0627\\u0644\\u062c\\u0646\\u0633"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"male","en":"male","ar":"\\u0630\\u0643\\u0631"}},{"option":{"fr":"female","en":"female","ar":"\\u0627\\u0646\\u062b\\u0649"}}]},"answer":{"fr":"female","en":"female","ar":"\\u0627\\u0646\\u062b\\u0649"}},{"type":"checkbox","data":{"label":{"fr":"tu est ?","en":"you are a ?","ar":"\\u0627\\u0646\\u062a :"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"etudiant","en":"student","ar":"\\u0637\\u0627\\u0644\\u0628"}},{"option":{"fr":"employ\\u00e9","en":"employe","ar":"\\u0645\\u0648\\u0638\\u0641"}},{"option":{"fr":"entrepreneur","en":"entrepreneur","ar":"\\u0631\\u062c\\u0644 \\u0627\\u0639\\u0645\\u0627\\u0644"}}]},"answer":[{"fr":"employ\\u00e9","en":"employe","ar":"\\u0645\\u0648\\u0638\\u0641"}]},{"type":"radio","data":{"label":{"fr":"Tranche d\'\\u00e2ge","en":"Age group","ar":"\\u0627\\u0644\\u0641\\u0626\\u0629 \\u0627\\u0644\\u0639\\u0645\\u0631\\u064a\\u0629"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"18-25","en":"18-25","ar":"18-25"}},{"option":{"fr":"25-32","en":"25-32","ar":"25-32"}},{"option":{"fr":"+32","en":"+32","ar":"+32"}}]},"answer":{"fr":"18-25","en":"18-25","ar":"18-25"}},{"type":"upload","data":{"label":{"fr":"CV","en":"CV","ar":"CV"},"description":{"fr":null,"en":null,"ar":null},"file_type":"image"},"answer":"a887ce39-4cfb-4b89-b4ee-03805cb04101"}]}]',
                'status' => 'approved',
                'created_at' => '2025-03-06 15:12:27',
                'updated_at' => '2025-03-06 15:12:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}