<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPPlanTiersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('plan_tiers')->delete();
        
        \DB::table('plan_tiers')->insert(array (
            0 => 
            array (
                'id' => 2,
                'title' => '{"fr":"SPONSORING PRINCIPAL SIFFP","en":"OFFICIAL SPONSOR SIFFP","ar":"الراعي الرسمي SIFFP"}',
                'created_at' => '2025-04-09 15:07:46',
                'updated_at' => '2025-04-15 15:22:44',
            ),
            1 => 
            array (
                'id' => 3,
                'title' => '{"fr":"SPONSORING ALTERNATIF SIFFP","en":"SPONSORING ALTERNATIF SIFFP","ar":"الرعاة البدلاء SIFFP"}',
                'created_at' => '2025-04-09 15:21:04',
                'updated_at' => '2025-04-15 15:23:26',
            ),
            2 => 
            array (
                'id' => 4,
                'title' => '{"fr":"SPONSORING PRINCIPAL CBE","en":"OFFICIAL SPONSOR CBE","ar":"الراعي الرسمي CBE"}',
                'created_at' => '2025-04-15 15:05:36',
                'updated_at' => '2025-04-15 15:05:36',
            ),
            3 => 
            array (
                'id' => 5,
                'title' => '{"fr":"SPONSORING ALTERNATIF CBE","en":"SPONSOR ALTERNATIVES CBE","ar":"SPONSOR ALTERNATIVES CBE"}',
                'created_at' => '2025-04-15 15:15:15',
                'updated_at' => '2025-04-15 15:24:14',
            ),
        ));
        
        
    }
}