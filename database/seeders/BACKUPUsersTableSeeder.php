<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPUsersTableSeeder extends Seeder
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
                'name' => 'super admin',
                'email' => 'registration@ffp-events.com',
                'verified_at' => '2025-02-15 17:47:56',
                'password' => '$2y$12$lWJt6bEL4ZZ2c2UqSsLwmeD1En8MJOVuMW4a/iEIKx9iD2eTn9Hxu',
                'remember_token' => 'fhQKM2dgCrlxte8kVDghSnCd2Gw6H6eSPbzIkW3nSipEE4PwqptStvKKAMsV',
                'created_at' => '2025-02-15 17:47:56',
                'updated_at' => '2025-04-27 01:14:42',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Ryma Benbrahim',
                'email' => 'r.benbrahim@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$LqAQzz2P7v0jtkYw3beFO.ovI52f8mH02oQRp1h4HVVOEv5NNG.im',
                'remember_token' => 'CGOvoC8F4QyeZG9qyGxI9MrE2nGuKjE3XXaxh8GBoKhdeFvHosLbjVmEzFR2',
                'created_at' => '2025-04-23 14:56:44',
                'updated_at' => '2025-04-27 13:44:21',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'Othmane Benaldi',
                'email' => 'othmane.benaldi@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$bHSJwHfd2BmkqQj32NwMH.y8ssfkKBSFyNBcHAdXRxQIKiO9qsoFe',
                'remember_token' => NULL,
                'created_at' => '2025-04-27 08:47:41',
                'updated_at' => '2025-04-27 08:47:41',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            3 => 
            array (
                'id' => 6,
                'name' => 'Hanaa Selidj',
                'email' => 'sellidj.hanaa@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$mTvZ36hMCgGT.gji5Dst2.y.GEmsJuLuSuwMgq3ewGMU54jO1Uu72',
                'remember_token' => NULL,
                'created_at' => '2025-04-27 13:46:19',
                'updated_at' => '2025-06-30 10:07:49',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            4 => 
            array (
                'id' => 7,
                'name' => 'Malik ',
                'email' => 'm.laddi@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$625L6EKBmVlHOYIC6ek2WO/Obtf70IXZ2QQaKQzapAYwJ9im/8Vvq',
                'remember_token' => NULL,
                'created_at' => '2025-05-25 12:48:03',
                'updated_at' => '2025-05-25 12:50:46',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            5 => 
            array (
                'id' => 9,
                'name' => 'Hotesse',
                'email' => 'rafik.kouiderhacene@gmail.com',
                'verified_at' => NULL,
                'password' => '$2y$12$PEaBF3kf.snkmPAIGfnxWeAFB6pLvIIidNFWqxMA1BgpLXQWIkM96',
                'remember_token' => NULL,
                'created_at' => '2025-07-26 15:15:12',
                'updated_at' => '2025-07-27 10:06:47',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            6 => 
            array (
                'id' => 10,
                'name' => 'hotesse test',
                'email' => 'hotesse01@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$Wn/qm59QqYZvDjjnG9dOqOcEaJJhHQYPRIIHYBRYeOvMDvdTfasvm',
                'remember_token' => NULL,
                'created_at' => '2025-07-26 15:28:15',
                'updated_at' => '2025-07-26 15:29:49',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
            7 => 
            array (
                'id' => 11,
                'name' => 'Khaled mubarak',
                'email' => 'khaled.m@ffp-events.com',
                'verified_at' => NULL,
                'password' => '$2y$12$2PZMFANwdARsjPXJibzKle6faUJMM14.X6AIQ5/qwWFJ0GhOVi7OC',
                'remember_token' => NULL,
                'created_at' => '2025-07-27 11:50:44',
                'updated_at' => '2025-07-27 11:53:15',
                'deleted_at' => NULL,
                'new_email' => NULL,
            ),
        ));
        
        
    }
}