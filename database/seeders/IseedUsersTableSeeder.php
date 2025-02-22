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
                'password' => '$2y$12$B/EPcomBBgw7kK06EEJ.K.tcgQYm9qskIbCy6Z6gGOst0DyOAyYHe',
                'remember_token' => 'J4qvvGplKntaaPHwVm56uOP4bnL44gmqu7WdxsLg80Q3C1hM5ddBFiFKmd8Y',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-02-22 17:27:12',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}