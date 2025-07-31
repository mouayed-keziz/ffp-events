<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPExportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exports')->delete();
        
        \DB::table('exports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'completed_at' => '2025-04-26 02:39:46',
                'file_disk' => 'local',
                'file_name' => 'export-1-logs',
                'exporter' => 'App\\Filament\\Exports\\LogExporter',
                'processed_rows' => 41,
                'total_rows' => 41,
                'successful_rows' => 41,
                'user_id' => 1,
                'created_at' => '2025-04-26 02:39:45',
                'updated_at' => '2025-04-26 02:39:46',
            ),
            1 => 
            array (
                'id' => 2,
                'completed_at' => '2025-04-26 23:24:10',
                'file_disk' => 'local',
                'file_name' => 'export-2-articles',
                'exporter' => 'App\\Filament\\Exports\\ArticleExporter',
                'processed_rows' => 1,
                'total_rows' => 1,
                'successful_rows' => 1,
                'user_id' => 1,
                'created_at' => '2025-04-26 23:24:06',
                'updated_at' => '2025-04-26 23:24:10',
            ),
            2 => 
            array (
                'id' => 4,
                'completed_at' => '2025-05-05 10:04:26',
                'file_disk' => 'local',
                'file_name' => 'export-4-exports',
                'exporter' => 'App\\Filament\\Exports\\ExportExporter',
                'processed_rows' => 2,
                'total_rows' => 2,
                'successful_rows' => 2,
                'user_id' => 1,
                'created_at' => '2025-05-05 10:04:26',
                'updated_at' => '2025-05-05 10:04:26',
            ),
            3 => 
            array (
                'id' => 10,
                'completed_at' => '2025-05-09 18:07:58',
                'file_disk' => 'local',
                'file_name' => 'export-10-event-announcements',
                'exporter' => 'App\\Filament\\Exports\\EventAnnouncementExporter',
                'processed_rows' => 2,
                'total_rows' => 2,
                'successful_rows' => 2,
                'user_id' => 1,
                'created_at' => '2025-05-09 18:07:56',
                'updated_at' => '2025-05-09 18:07:58',
            ),
            4 => 
            array (
                'id' => 33,
                'completed_at' => '2025-06-20 18:05:48',
                'file_disk' => 'local',
                'file_name' => 'export-33-event-announcements',
                'exporter' => 'App\\Filament\\Exports\\EventAnnouncementExporter',
                'processed_rows' => 2,
                'total_rows' => 2,
                'successful_rows' => 2,
                'user_id' => 1,
                'created_at' => '2025-06-20 18:05:47',
                'updated_at' => '2025-06-20 18:05:48',
            ),
            5 => 
            array (
                'id' => 37,
                'completed_at' => '2025-07-01 03:18:48',
                'file_disk' => 'local',
                'file_name' => 'export-37-event-announcements',
                'exporter' => 'App\\Filament\\Exports\\EventAnnouncementExporter',
                'processed_rows' => 2,
                'total_rows' => 2,
                'successful_rows' => 2,
                'user_id' => 1,
                'created_at' => '2025-07-01 03:18:45',
                'updated_at' => '2025-07-01 03:18:48',
            ),
            6 => 
            array (
                'id' => 93,
                'completed_at' => '2025-07-13 18:04:36',
                'file_disk' => 'local',
                'file_name' => 'export-93-exhibitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\ExhibitorSubmissionExporter',
                'processed_rows' => 37,
                'total_rows' => 37,
                'successful_rows' => 37,
                'user_id' => 1,
                'created_at' => '2025-07-13 18:04:33',
                'updated_at' => '2025-07-13 18:04:36',
            ),
            7 => 
            array (
                'id' => 94,
                'completed_at' => '2025-07-13 18:06:06',
                'file_disk' => 'local',
                'file_name' => 'export-94-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 4,
                'total_rows' => 4,
                'successful_rows' => 4,
                'user_id' => 1,
                'created_at' => '2025-07-13 18:06:05',
                'updated_at' => '2025-07-13 18:06:06',
            ),
            8 => 
            array (
                'id' => 95,
                'completed_at' => '2025-07-13 22:55:42',
                'file_disk' => 'local',
                'file_name' => 'export-95-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 4,
                'total_rows' => 4,
                'successful_rows' => 4,
                'user_id' => 5,
                'created_at' => '2025-07-13 22:55:42',
                'updated_at' => '2025-07-13 22:55:42',
            ),
            9 => 
            array (
                'id' => 96,
                'completed_at' => '2025-07-13 22:57:16',
                'file_disk' => 'local',
                'file_name' => 'export-96-exhibitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\ExhibitorSubmissionExporter',
                'processed_rows' => 3,
                'total_rows' => 3,
                'successful_rows' => 3,
                'user_id' => 5,
                'created_at' => '2025-07-13 22:57:14',
                'updated_at' => '2025-07-13 22:57:16',
            ),
            10 => 
            array (
                'id' => 97,
                'completed_at' => '2025-07-14 15:56:57',
                'file_disk' => 'local',
                'file_name' => 'export-97-visitors',
                'exporter' => 'App\\Filament\\Exports\\VisitorExporter',
                'processed_rows' => 10,
                'total_rows' => 10,
                'successful_rows' => 10,
                'user_id' => 5,
                'created_at' => '2025-07-14 15:56:55',
                'updated_at' => '2025-07-14 15:56:57',
            ),
            11 => 
            array (
                'id' => 98,
                'completed_at' => '2025-07-14 16:00:52',
                'file_disk' => 'local',
                'file_name' => 'export-98-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 5,
                'total_rows' => 5,
                'successful_rows' => 5,
                'user_id' => 5,
                'created_at' => '2025-07-14 16:00:51',
                'updated_at' => '2025-07-14 16:00:52',
            ),
            12 => 
            array (
                'id' => 99,
                'completed_at' => '2025-07-14 16:09:01',
                'file_disk' => 'local',
                'file_name' => 'export-99-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 5,
                'total_rows' => 5,
                'successful_rows' => 5,
                'user_id' => 5,
                'created_at' => '2025-07-14 16:08:58',
                'updated_at' => '2025-07-14 16:09:01',
            ),
            13 => 
            array (
                'id' => 100,
                'completed_at' => '2025-07-14 16:10:50',
                'file_disk' => 'local',
                'file_name' => 'export-100-exhibitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\ExhibitorSubmissionExporter',
                'processed_rows' => 3,
                'total_rows' => 3,
                'successful_rows' => 3,
                'user_id' => 5,
                'created_at' => '2025-07-14 16:10:48',
                'updated_at' => '2025-07-14 16:10:50',
            ),
            14 => 
            array (
                'id' => 104,
                'completed_at' => '2025-07-27 12:10:57',
                'file_disk' => 'local',
                'file_name' => 'export-104-visitors',
                'exporter' => 'App\\Filament\\Exports\\VisitorExporter',
                'processed_rows' => 121,
                'total_rows' => 121,
                'successful_rows' => 121,
                'user_id' => 11,
                'created_at' => '2025-07-27 12:10:54',
                'updated_at' => '2025-07-27 12:10:57',
            ),
            15 => 
            array (
                'id' => 105,
                'completed_at' => '2025-07-27 12:12:24',
                'file_disk' => 'local',
                'file_name' => 'export-105-visitors',
                'exporter' => 'App\\Filament\\Exports\\VisitorExporter',
                'processed_rows' => 121,
                'total_rows' => 121,
                'successful_rows' => 121,
                'user_id' => 5,
                'created_at' => '2025-07-27 12:12:22',
                'updated_at' => '2025-07-27 12:12:24',
            ),
            16 => 
            array (
                'id' => 106,
                'completed_at' => '2025-07-28 11:47:02',
                'file_disk' => 'local',
                'file_name' => 'export-106-visitors',
                'exporter' => 'App\\Filament\\Exports\\VisitorExporter',
                'processed_rows' => 128,
                'total_rows' => 128,
                'successful_rows' => 128,
                'user_id' => 11,
                'created_at' => '2025-07-28 11:47:01',
                'updated_at' => '2025-07-28 11:47:02',
            ),
            17 => 
            array (
                'id' => 107,
                'completed_at' => '2025-07-28 11:47:17',
                'file_disk' => 'local',
                'file_name' => 'export-107-visitors',
                'exporter' => 'App\\Filament\\Exports\\VisitorExporter',
                'processed_rows' => 128,
                'total_rows' => 128,
                'successful_rows' => 128,
                'user_id' => 11,
                'created_at' => '2025-07-28 11:47:15',
                'updated_at' => '2025-07-28 11:47:17',
            ),
            18 => 
            array (
                'id' => 116,
                'completed_at' => '2025-07-29 23:55:54',
                'file_disk' => 'local',
                'file_name' => 'export-116-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 53,
                'total_rows' => 53,
                'successful_rows' => 53,
                'user_id' => 1,
                'created_at' => '2025-07-29 23:55:51',
                'updated_at' => '2025-07-29 23:55:54',
            ),
            19 => 
            array (
                'id' => 117,
                'completed_at' => '2025-07-30 00:07:01',
                'file_disk' => 'local',
                'file_name' => 'export-117-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 53,
                'total_rows' => 53,
                'successful_rows' => 53,
                'user_id' => 1,
                'created_at' => '2025-07-30 00:06:58',
                'updated_at' => '2025-07-30 00:07:01',
            ),
            20 => 
            array (
                'id' => 118,
                'completed_at' => '2025-07-30 00:16:20',
                'file_disk' => 'local',
                'file_name' => 'export-118-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 53,
                'total_rows' => 53,
                'successful_rows' => 53,
                'user_id' => 1,
                'created_at' => '2025-07-30 00:16:17',
                'updated_at' => '2025-07-30 00:16:20',
            ),
            21 => 
            array (
                'id' => 119,
                'completed_at' => '2025-07-30 00:24:51',
                'file_disk' => 'local',
                'file_name' => 'export-119-visitor-submissions',
                'exporter' => 'App\\Filament\\Exports\\VisitorSubmissionExporter',
                'processed_rows' => 53,
                'total_rows' => 53,
                'successful_rows' => 53,
                'user_id' => 1,
                'created_at' => '2025-07-30 00:24:49',
                'updated_at' => '2025-07-30 00:24:51',
            ),
        ));
        
        
    }
}