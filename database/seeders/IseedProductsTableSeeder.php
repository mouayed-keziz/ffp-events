<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '{"fr":"Table scandinave 20 x 80 CM pour 4 personnes","en":"Table scandinave 20 x 80 CM pour 4 personnes","ar":"Table scandinave 20 x 80 CM pour 4 personnes"}',
                'code' => 'table01',
                'created_at' => '2025-02-17 19:35:01',
                'updated_at' => '2025-02-17 19:35:01',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '{"fr":"Chaises scandinaves","en":"Chaises scandinaves","ar":"Chaises scandinaves"}',
                'code' => 'chaises01 ',
                'created_at' => '2025-02-17 19:35:34',
                'updated_at' => '2025-02-17 19:35:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '{"fr":"Tapis 100 x 120 CM","en":"Tapis 100 x 120 CM","ar":"Tapis 100 x 120 CM"}',
                'code' => 'tapis01',
                'created_at' => '2025-02-17 19:36:12',
                'updated_at' => '2025-02-17 19:36:12',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '{"fr":"something special","en":"something special","ar":"something special"}',
                'code' => 'something-special',
                'created_at' => '2025-03-06 15:19:27',
                'updated_at' => '2025-03-06 15:19:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}