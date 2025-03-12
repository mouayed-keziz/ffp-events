<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@admin.dev',
                'verified_at' => '2025-02-15 17:47:56',
                'password' => '$2y$12$lWJt6bEL4ZZ2c2UqSsLwmeD1En8MJOVuMW4a/iEIKx9iD2eTn9Hxu',
                'remember_token' => 'PrlcOIVvwBaz7V4nizIB02PKg1BO2re5z8ZMataEvHnMfm1AKZXlB5LJ07md',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-03-06 14:44:09',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}