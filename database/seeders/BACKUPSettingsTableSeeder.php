<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group' => 'default',
                'name' => 'name',
                'locked' => 0,
                'payload' => '"FFP EVENTS"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            1 => 
            array (
                'id' => 2,
                'group' => 'default',
                'name' => 'email',
                'locked' => 0,
                'payload' => '"registration@ffp-events.com"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            2 => 
            array (
                'id' => 3,
                'group' => 'default',
                'name' => 'phone',
                'locked' => 0,
                'payload' => '"+213560873472"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            3 => 
            array (
                'id' => 4,
                'group' => 'default',
                'name' => 'address',
                'locked' => 0,
                'payload' => '"El Achour"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            4 => 
            array (
                'id' => 5,
                'group' => 'default',
                'name' => 'city',
                'locked' => 0,
                'payload' => '"Alger"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            5 => 
            array (
                'id' => 6,
                'group' => 'default',
                'name' => 'state',
                'locked' => 0,
                'payload' => '"Alger"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            6 => 
            array (
                'id' => 7,
                'group' => 'default',
                'name' => 'zip',
                'locked' => 0,
                'payload' => '"16000"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            7 => 
            array (
                'id' => 8,
                'group' => 'default',
                'name' => 'country',
                'locked' => 0,
                'payload' => '"Algeria"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            8 => 
            array (
                'id' => 9,
                'group' => 'default',
                'name' => 'applicationTerms',
                'locked' => 0,
                'payload' => '"<p>These are the default application terms and conditions.<\\/p><p><br><\\/p>"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            9 => 
            array (
                'id' => 10,
                'group' => 'default',
                'name' => 'facebookLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.facebook.com\\/share\\/19SVQNjnN1\\/"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            10 => 
            array (
                'id' => 11,
                'group' => 'default',
                'name' => 'linkedinLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.linkedin.com\\/company\\/ffpevents"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            11 => 
            array (
                'id' => 12,
                'group' => 'default',
                'name' => 'instagramLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.instagram.com\\/ffpevents"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            12 => 
            array (
                'id' => 13,
                'group' => 'default',
                'name' => 'detailedAddress',
                'locked' => 0,
                'payload' => '"Cit\\u00e9 20 Aout 55 Ouest romaine N\\u00b076, 1er \\u00e9tage"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            13 => 
            array (
                'id' => 14,
                'group' => 'default',
                'name' => 'location',
                'locked' => 0,
            'payload' => '"El Achour (Alger), Alg\\u00e9rie"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            14 => 
            array (
                'id' => 15,
                'group' => 'default',
                'name' => 'capital',
                'locked' => 0,
                'payload' => '"Capital Social: 100 000,00 DA"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            15 => 
            array (
                'id' => 16,
                'group' => 'default',
                'name' => 'rc',
                'locked' => 0,
                'payload' => '"RC N\\u00b0 : 17\\/01123\\/00-16"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            16 => 
            array (
                'id' => 17,
                'group' => 'default',
                'name' => 'nif',
                'locked' => 0,
                'payload' => '"NIF: 001716101234114"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            17 => 
            array (
                'id' => 18,
                'group' => 'default',
                'name' => 'ai',
                'locked' => 0,
                'payload' => '"AI : 16540510921"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            18 => 
            array (
                'id' => 19,
                'group' => 'default',
                'name' => 'nis',
                'locked' => 0,
                'payload' => '"NIS: 001716501893038"',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            19 => 
            array (
                'id' => 20,
                'group' => 'default',
                'name' => 'tva',
                'locked' => 0,
                'payload' => '19',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            20 => 
            array (
                'id' => 21,
                'group' => 'default',
                'name' => 'faq',
                'locked' => 0,
                'payload' => '[{"question":"What is FFP Events?","answer":"FFP Events is a platform that allows you to create and manage events."},{"question":"How can I create an event?","answer":"You can create an event by clicking on the \\"Create Event\\" button in the dashboard."},{"question":"How can I manage my events?","answer":"You can manage your events by going to the \\"My Events\\" section in the dashboard."},{"question":"How can I buy tickets for an event?","answer":"You can buy tickets for an event by clicking on the \\"Buy Tickets\\" button on the event page."},{"question":"How can I contact FFP Events?","answer":"You can contact FFP Events by sending an email to"}]',
                'created_at' => '2025-03-30 06:24:01',
                'updated_at' => '2025-07-22 15:36:54',
            ),
            21 => 
            array (
                'id' => 127,
                'group' => 'default',
                'name' => 'jobs',
                'locked' => 0,
            'payload' => '[{"ar":"\\u0627\\u0644\\u0631\\u0626\\u064a\\u0633 \\u0627\\u0644\\u062a\\u0646\\u0641\\u064a\\u0630\\u064a","fr":"CEO","en":"CEO"},{"ar":"\\u0627\\u0644\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0639\\u0627\\u0645","fr":"Directeur G\\u00e9n\\u00e9ral (DG)","en":"General Manager"},{"ar":"\\u0627\\u0644\\u0631\\u0626\\u064a\\u0633 \\u0627\\u0644\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0639\\u0627\\u0645","fr":"Pr\\u00e9sident Directeur G\\u00e9n\\u00e9ral (PDG)","en":"Chairman and CEO"},{"ar":"\\u0645\\u0633\\u064a\\u0631","fr":"G\\u00e9rant","en":"Manager"},{"ar":"\\u0645\\u0633\\u064a\\u0631 \\u0645\\u0634\\u0627\\u0631\\u0643","fr":"Co-g\\u00e9rant","en":"Co-Manager"},{"ar":"\\u0645\\u0624\\u0633\\u0633","fr":"Fondateur","en":"Founder"},{"ar":"\\u0645\\u0624\\u0633\\u0633 \\u0645\\u0634\\u0627\\u0631\\u0643","fr":"Co-Fondateur","en":"Co-Founder"},{"ar":"\\u0634\\u0631\\u064a\\u0643","fr":"Associ\\u00e9","en":"Partner"},{"ar":"\\u0645\\u0647\\u0646\\u062f\\u0633 \\u0645\\u0639\\u0645\\u0627\\u0631\\u064a","fr":"Architecte","en":"Architect"},{"ar":"\\u0646\\u062c\\u0627\\u0631","fr":"Menuisier","en":"Carpenter"},{"ar":"\\u062a\\u0627\\u062c\\u0631","fr":"Commer\\u00e7ant","en":"Merchant"},{"ar":"\\u0645\\u0647\\u0646\\u062f\\u0633","fr":"Ing\\u00e9nieur","en":"Engineer"},{"ar":"\\u0631\\u0627\\u0626\\u062f \\u0623\\u0639\\u0645\\u0627\\u0644","fr":"Entrepreneur","en":"Entrepreneur"},{"ar":"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062a\\u0635\\u062f\\u064a\\u0631","fr":"Directeur Export \\/ Export Manager","en":"Export Director \\/ Export Manager"},{"ar":"\\u0631\\u0626\\u064a\\u0633 \\u0642\\u0633\\u0645 \\u0627\\u0644\\u0648\\u0635\\u0641\\u0627\\u062a \\u0627\\u0644\\u0637\\u0628\\u064a\\u0629","fr":"Chef de d\\u00e9partement Prescription","en":"Prescription Department Head"},{"ar":"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062f\\u0631\\u0627\\u0633\\u0627\\u062a","fr":"Directeur des \\u00c9tudes","en":"Studies Director"},{"ar":"\\u0627\\u0644\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062a\\u0642\\u0646\\u064a","fr":"Directeur Technique","en":"Technical Director"},{"ar":"\\u0627\\u0644\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062a\\u062c\\u0627\\u0631\\u064a","fr":"Directeur Commercial","en":"Commercial Director"},{"ar":"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642 \\u0648\\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644","fr":"Directeur Marketing et Communication","en":"Marketing and Communication Director"},{"ar":"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631","fr":"Directeur D\\u00e9veloppement","en":"Development Director"},{"ar":"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0627\\u062a","fr":"Directeur des Op\\u00e9rations","en":"Operations Director"},{"ar":"\\u0627\\u0644\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a","fr":"Directeur Financier","en":"Financial Director"},{"ar":"\\u0627\\u0644\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u062a\\u0642\\u0646\\u064a","fr":"Responsable Technique","en":"Technical Manager"},{"ar":"\\u0627\\u0644\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u062a\\u062c\\u0627\\u0631\\u064a","fr":"Responsable Commercial","en":"Commercial Manager"},{"ar":"\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642 \\u0648\\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644","fr":"Responsable Marketing et Communication","en":"Marketing and Communication Manager"},{"ar":"\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631","fr":"Responsable D\\u00e9veloppement","en":"Development Manager"},{"ar":"\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0627\\u062a","fr":"Responsable des Op\\u00e9rations","en":"Operations Manager"},{"ar":"\\u0627\\u0644\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a","fr":"Responsable Financier","en":"Financial Manager"},{"ar":"\\u0645\\u0633\\u0624\\u0648\\u0644 \\u0627\\u0644\\u0645\\u0634\\u062a\\u0631\\u064a\\u0627\\u062a","fr":"Responsable des Achats","en":"Purchasing Manager"},{"ar":"\\u0645\\u0646\\u062f\\u0648\\u0628 \\u062a\\u062c\\u0627\\u0631\\u064a","fr":"Commercial \\/ D\\u00e9l\\u00e9gu\\u00e9","en":"Sales Representative"},{"ar":"\\u0645\\u0646\\u062f\\u0648\\u0628 \\u062a\\u062c\\u0627\\u0631\\u064a \\u062a\\u0642\\u0646\\u064a","fr":"Technico-commercial","en":"Technical Sales Representative"},{"ar":"\\u0645\\u0643\\u0644\\u0641 \\u0628\\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642 \\u0648\\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644","fr":"Charg\\u00e9 Marketing et Communication","en":"Marketing and Communication Officer"},{"ar":"\\u0645\\u0643\\u0644\\u0641 \\u0628\\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631","fr":"Charg\\u00e9 du D\\u00e9veloppement","en":"Development Officer"},{"ar":"\\u0645\\u0643\\u0644\\u0641 \\u0628\\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0627\\u062a","fr":"Charg\\u00e9 des Op\\u00e9rations","en":"Operations Officer"},{"ar":"\\u0645\\u0643\\u0644\\u0641 \\u0628\\u0627\\u0644\\u0645\\u0634\\u062a\\u0631\\u064a\\u0627\\u062a","fr":"Charg\\u00e9 des Achats","en":"Purchasing Officer"},{"ar":"\\u0637\\u0627\\u0644\\u0628","fr":"\\u00c9tudiant","en":"Student"},{"ar":"\\u0645\\u0648\\u0638\\u0641 \\u062d\\u0643\\u0648\\u0645\\u064a","fr":"Fonctionnaire","en":"Civil Servant"},{"ar":"\\u0645\\u0633\\u062a\\u0634\\u0627\\u0631","fr":"Consultant","en":"Consultant"}]',
                'created_at' => '2025-07-11 01:42:25',
                'updated_at' => '2025-07-22 15:36:54',
            ),
        ));
        
        
    }
}