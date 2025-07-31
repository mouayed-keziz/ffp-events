<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPFailedImportRowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('failed_import_rows')->delete();
        
        
        
    }
}