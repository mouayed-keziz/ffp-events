<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedArticlesTableSeeder extends Seeder
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
                'id' => 1,
                'title' => '{"fr":"aze","en":"qsd","ar":"wxc"}',
                'slug' => 'azeaze',
                'description' => '{"fr":"wxc","en":"qsd","ar":"aze"}',
                'content' => '{"fr":"<p>qsd</p>","en":"<p>wxc</p>","ar":"<p>aze</p>"}',
                'published_at' => '2025-02-13 00:00:00',
                'created_at' => '2025-02-15 21:50:54',
                'updated_at' => '2025-02-15 21:50:54',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}