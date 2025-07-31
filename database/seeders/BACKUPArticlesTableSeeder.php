<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('articles')->delete();
        
        \DB::table('articles')->insert(array (
            0 => 
            array (
                'id' => 4,
                'title' => '{"fr":"article de test","en":"test article","ar":"مقال تجريبي"}',
                'slug' => 'test',
                'description' => '{"fr":"test","en":"test","ar":"test"}',
                'content' => '{"fr":"<p>test</p>","en":"<p>test</p>","ar":"<p>test</p>"}',
                'published_at' => '2025-04-24 00:00:00',
                'created_at' => '2025-04-25 19:40:32',
                'updated_at' => '2025-04-27 13:35:58',
                'deleted_at' => '2025-04-27 13:35:58',
            ),
        ));
        
        
    }
}