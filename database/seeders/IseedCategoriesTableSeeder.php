<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '{"fr":"Organisation","en":"Organization","ar":"تنظيم"}',
                'slug' => 'organisation',
                'created_at' => '2025-03-11 21:35:03',
                'updated_at' => '2025-03-11 22:01:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '{"fr":"Événements","en":"Events","ar":"أحداث"}',
                'slug' => 'evenements',
                'created_at' => '2025-03-11 21:50:26',
                'updated_at' => '2025-03-11 22:01:19',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '{"fr":"Équipe","en":"Team","ar":"فريق"}',
                'slug' => 'equipe',
                'created_at' => '2025-03-11 21:50:49',
                'updated_at' => '2025-03-11 22:01:33',
            ),
            3 => 
            array (
                'id' => 7,
                'name' => '{"fr":"Préparation","en":"Preparation","ar":"إعداد"}',
                'slug' => 'preparation',
                'created_at' => '2025-03-11 21:58:58',
                'updated_at' => '2025-03-11 21:58:58',
            ),
            4 => 
            array (
                'id' => 8,
                'name' => '{"fr":"Pré-événement","en":"Pre-event","ar":"ما قبل الحدث"}',
                'slug' => 'pre-evenement',
                'created_at' => '2025-03-11 21:59:53',
                'updated_at' => '2025-03-11 21:59:53',
            ),
            5 => 
            array (
                'id' => 9,
                'name' => '{"fr":"Post-événement","en":"Post-event","ar":"ما بعد الحدث"}',
                'slug' => 'post-evenement',
                'created_at' => '2025-03-11 22:00:01',
                'updated_at' => '2025-03-11 22:00:01',
            ),
            6 => 
            array (
                'id' => 10,
                'name' => '{"fr":"Planification","en":"Planning","ar":"تخطيط"}',
                'slug' => 'planification',
                'created_at' => '2025-03-11 22:06:22',
                'updated_at' => '2025-03-11 22:06:22',
            ),
            7 => 
            array (
                'id' => 11,
                'name' => '{"fr":"Coordination","en":"Coordination","ar":"تنسيق"}',
                'slug' => 'coordination',
                'created_at' => '2025-03-11 22:06:28',
                'updated_at' => '2025-03-11 22:06:28',
            ),
            8 => 
            array (
                'id' => 12,
                'name' => '{"fr":"Logistique","en":"Logistics","ar":"لوجستيات"}',
                'slug' => 'logistique',
                'created_at' => '2025-03-11 22:06:38',
                'updated_at' => '2025-03-11 22:06:38',
            ),
            9 => 
            array (
                'id' => 13,
                'name' => '{"fr":"Budget","en":"Budget","ar":"ميزانية"}',
                'slug' => 'budget',
                'created_at' => '2025-03-11 22:06:46',
                'updated_at' => '2025-03-11 22:06:46',
            ),
            10 => 
            array (
                'id' => 14,
                'name' => '{"fr":"Inscription","en":"Registration","ar":"تسجيل"}',
                'slug' => 'inscription',
                'created_at' => '2025-03-11 22:06:53',
                'updated_at' => '2025-03-11 22:06:53',
            ),
            11 => 
            array (
                'id' => 15,
                'name' => '{"fr":"Invitation","en":"Invitation","ar":"دعوة"}',
                'slug' => 'invitation',
                'created_at' => '2025-03-11 22:07:04',
                'updated_at' => '2025-03-11 22:07:04',
            ),
            12 => 
            array (
                'id' => 16,
                'name' => '{"fr":"Programme","en":"Program/Schedule","ar":"Program/Schedule"}',
                'slug' => 'programme',
                'created_at' => '2025-03-11 22:07:15',
                'updated_at' => '2025-03-11 22:07:15',
            ),
            13 => 
            array (
                'id' => 17,
                'name' => '{"fr":"Communication","en":"Communication","ar":"اتصالات"}',
                'slug' => 'communication',
                'created_at' => '2025-03-11 22:07:23',
                'updated_at' => '2025-03-11 22:07:23',
            ),
            14 => 
            array (
                'id' => 18,
                'name' => '{"fr":"Sponsors","en":"Sponsors","ar":"رعاة"}',
                'slug' => 'sponsors',
                'created_at' => '2025-03-11 22:07:31',
                'updated_at' => '2025-03-11 22:07:31',
            ),
            15 => 
            array (
                'id' => 19,
                'name' => '{"fr":"Partenaires","en":"شركاء","ar":"Partners"}',
                'slug' => 'partenaires',
                'created_at' => '2025-03-11 22:07:39',
                'updated_at' => '2025-03-11 22:07:39',
            ),
        ));
        
        
    }
}