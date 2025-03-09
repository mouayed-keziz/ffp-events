<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedSettingsTableSeeder extends Seeder
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
                'payload' => '"FFP-EVENTS"',
                'created_at' => '2025-02-15 17:47:55',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            1 => 
            array (
                'id' => 2,
                'group' => 'default',
                'name' => 'email',
                'locked' => 0,
                'payload' => '"contact@ffpevents.dz"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            2 => 
            array (
                'id' => 3,
                'group' => 'default',
                'name' => 'phone',
                'locked' => 0,
                'payload' => '"+213555555555"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            3 => 
            array (
                'id' => 4,
                'group' => 'default',
                'name' => 'address',
                'locked' => 0,
                'payload' => '"123 Rue Didouche Mourad"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            4 => 
            array (
                'id' => 5,
                'group' => 'default',
                'name' => 'city',
                'locked' => 0,
                'payload' => '"Alger"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            5 => 
            array (
                'id' => 6,
                'group' => 'default',
                'name' => 'state',
                'locked' => 0,
                'payload' => '"Alger"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            6 => 
            array (
                'id' => 7,
                'group' => 'default',
                'name' => 'zip',
                'locked' => 0,
                'payload' => '"16000"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            7 => 
            array (
                'id' => 8,
                'group' => 'default',
                'name' => 'country',
                'locked' => 0,
                'payload' => '"Algeria"',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            8 => 
            array (
                'id' => 9,
                'group' => 'default',
                'name' => 'faq',
                'locked' => 0,
                'payload' => '[{"question":"How can I create an event?","answer":"You can create an event by clicking on the \\"Create Event\\" button in the dashboard."},{"question":"keziz mouayed","answer":"FFP Events is a platform that allows you to create and manage events."},{"question":"How can I manage my events?","answer":"You can manage your events by going to the \\"My Events\\" section in the dashboard."},{"question":"How can I buy tickets for an event?","answer":"You can buy tickets for an event by clicking on the \\"Buy Tickets\\" button on the event page."},{"question":"How can I contact FFP Events?","answer":"You can contact FFP Events by sending an email to"}]',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-23 19:22:27',
            ),
            9 => 
            array (
                'id' => 19,
                'group' => 'default',
                'name' => 'applicationTerms',
                'locked' => 0,
                'payload' => '"<p>These are the default application terms and conditions.<\\/p>"',
                'created_at' => '2025-03-09 20:37:07',
                'updated_at' => '2025-03-09 20:37:07',
            ),
            10 => 
            array (
                'id' => 20,
                'group' => 'default',
                'name' => 'facebookLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.facebook.com\\/ffpevents"',
                'created_at' => '2025-03-09 20:37:07',
                'updated_at' => '2025-03-09 20:37:07',
            ),
            11 => 
            array (
                'id' => 21,
                'group' => 'default',
                'name' => 'linkedinLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.linkedin.com\\/company\\/ffpevents"',
                'created_at' => '2025-03-09 20:37:07',
                'updated_at' => '2025-03-09 20:37:07',
            ),
            12 => 
            array (
                'id' => 22,
                'group' => 'default',
                'name' => 'instagramLink',
                'locked' => 0,
                'payload' => '"https:\\/\\/www.instagram.com\\/ffpevents"',
                'created_at' => '2025-03-09 20:37:07',
                'updated_at' => '2025-03-09 20:37:07',
            ),
        ));
        
        
    }
}