<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPExhibitorPostPaymentFormsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exhibitor_post_payment_forms')->delete();
        
        \DB::table('exhibitor_post_payment_forms')->insert(array (
            0 => 
            array (
                'id' => 2,
                'title' => '{"fr":"Informations catalogue","en":"Catalog informations","ar":"معلومات الكتالوج"}',
                'description' => '{"ar":null}',
            'sections' => '[{"title":{"fr":"Informations de l\'entreprise","en":"Company Informations","ar":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0646 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"fields":[{"type":"input","data":{"label":{"fr":"Nom complet de l\\u2019entreprise (raison sociale)","en":"Full name of the company (legal name) ","ar":"\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644 \\u0644\\u0644\\u0634\\u0631\\u0643\\u0629 (\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a)"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Adresse compl\\u00e8te du si\\u00e8ge social","en":"Full address of the registered office","ar":"\\u0627\\u0644\\u0639\\u0648\\u0627\\u0646 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644 \\u0644\\u0645\\u0642\\u0631 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Email","en":"Email","ar":"\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"email","required":true}},{"type":"input","data":{"label":{"fr":"Fixe","en":"landline number","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641 \\u0627\\u0644\\u062b\\u0627\\u0628\\u062a"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Mobile","en":"Phone Number","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Site web","en":"Website","ar":"\\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u0627\\u0644\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pr\\u00e9sentation de l\\u2019entreprise (en 3 lignes)","en":"Presentation of the company (in three lines)","ar":"\\u062a\\u0639\\u0631\\u064a\\u0641 \\u0628\\u0627\\u0644\\u0634\\u0631\\u0643\\u0629 (\\u0641\\u064a \\u062b\\u0644\\u0627\\u062b\\u0629 \\u0623\\u0633\\u0637\\u0631)"},"description":{"fr":null,"en":null,"ar":null},"type":"paragraph","required":true}},{"type":"input","data":{"label":{"fr":"Secteurs d\\u2019activit\\u00e9 de l\\u2019entreprise","en":"Business sectors of the company","ar":"\\u0642\\u0637\\u0627\\u0639\\u0627\\u062a \\u0646\\u0634\\u0627\\u0637 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"paragraph","required":true}}]}]',
                'event_announcement_id' => 4,
                'created_at' => '2025-04-27 13:25:08',
                'updated_at' => '2025-07-29 10:42:33',
            ),
            1 => 
            array (
                'id' => 5,
                'title' => '{"fr":"Informations catalogue","en":"Catalog informations","ar":"معلومات الكتالوج"}',
                'description' => '{"ar":null}',
            'sections' => '[{"title":{"fr":"Informations de l\'entreprise","en":"Company Informations","ar":"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0646 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"fields":[{"type":"input","data":{"label":{"fr":"Nom complet de l\\u2019entreprise (raison sociale)","en":"Full name of the company (legal name) ","ar":"\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644 \\u0644\\u0644\\u0634\\u0631\\u0643\\u0629 (\\u0627\\u0644\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a)"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Adresse compl\\u00e8te du si\\u00e8ge social","en":"Full address of the registered office","ar":"\\u0627\\u0644\\u0639\\u0646\\u0648\\u0627\\u0646 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644 \\u0644\\u0645\\u0642\\u0631 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Email","en":"Email","ar":"\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"email","required":true}},{"type":"input","data":{"label":{"fr":"Fixe","en":"landline number","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641 \\u0627\\u0644\\u062b\\u0627\\u0628\\u062a"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Mobile","en":"Phone Number","ar":"\\u0631\\u0642\\u0645 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641"},"description":{"fr":null,"en":null,"ar":null},"type":"phone","required":true}},{"type":"input","data":{"label":{"fr":"Site web","en":"Website","ar":"\\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u0627\\u0644\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true}},{"type":"input","data":{"label":{"fr":"Pr\\u00e9sentation de l\\u2019entreprise (en 3 lignes)","en":"Presentation of the company (in three lines)","ar":"\\u062a\\u0639\\u0631\\u064a\\u0641 \\u0628\\u0627\\u0644\\u0634\\u0631\\u0643\\u0629 (\\u0641\\u064a \\u062b\\u0644\\u0627\\u062b\\u0629 \\u0623\\u0633\\u0637\\u0631)"},"description":{"fr":null,"en":null,"ar":null},"type":"paragraph","required":true}},{"type":"input","data":{"label":{"fr":"Secteurs d\\u2019activit\\u00e9 de l\\u2019entreprise","en":"Business sectors of the company","ar":"\\u0642\\u0637\\u0627\\u0639\\u0627\\u062a \\u0646\\u0634\\u0627\\u0637 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0629"},"description":{"fr":null,"en":null,"ar":null},"type":"paragraph","required":true}}]}]',
                'event_announcement_id' => 1,
                'created_at' => '2025-07-29 00:19:02',
                'updated_at' => '2025-07-29 10:42:57',
            ),
        ));
        
        
    }
}