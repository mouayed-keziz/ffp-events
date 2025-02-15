<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedActivityLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_log')->delete();
        
        \DB::table('activity_log')->insert(array (
            0 => 
            array (
                'id' => 1,
                'log_name' => 'authentication',
                'description' => 'User logged in',
                'subject_type' => NULL,
                'subject_id' => NULL,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"email":"admin@admin.dev","name":"Admin"}',
                'created_at' => '2025-02-15 17:48:24',
                'updated_at' => '2025-02-15 17:48:24',
                'event' => 'login',
                'batch_uuid' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'log_name' => 'event_announcements',
                'description' => 'Création d\'un nouvel événement',
                'subject_type' => 'App\\Models\\EventAnnouncement',
                'subject_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"fr":{"title":"DZ BUILD","description":"Salon de l\\u2019innovation du batiment et des materiaux de construction","content":"<p>Le DZ Build est un \\u00e9v\\u00e9nement sans pr\\u00e9c\\u00e9dent qui r\\u00e9unit les professionnels de l\'industrie du b\\u00e2timent et des<br>mat\\u00e9riaux de construction pour discuter de la derni\\u00e8re innovation. Le Salon de l\'innovation du batiment et des<br>materiaux de construction est une plateforme unique permettant aux exposants d\'exposer leurs derni\\u00e8res<br>technologies et solutions, tandis que les visiteurs peuvent d\\u00e9couvrir les derniers d\\u00e9veloppements dans le domaine.<br>Au cours de cette journ\\u00e9e exceptionnelle, nous vous pr\\u00e9senterons les derni\\u00e8res innovations qui transformeront la<br>face du b\\u00e2timent et des mat\\u00e9riaux de construction pour un avenir plus durable et plus respectueux de<br>l\'environnement.<\\/p>","terms":""},"en":{"title":"DZ BUILD","description":"Salon de l\\u2019innovation du batiment et des materiaux de construction","content":"<p>DZ Build is an unprecedented event that brings together industry professionals in the building and construction<br>materials sector to discuss the latest innovations. The Building Innovation and Construction Materials Expo is a<br>unique platform allowing exhibitors to showcase their latest technologies and solutions, while visitors can<br>discover the latest developments in the field. Throughout this exceptional day, we will present the latest<br>innovations that are set to transform the face of building and construction for a more sustainable and<br>environmentally-friendly future.<\\/p>","terms":""},"ar":{"title":"DZ BUILD","description":"Salon de l\\u2019innovation du batiment et des materiaux de construction","content":"<p dir=\\"rtl\\">\\u0647\\u0648 DZ Build \\u062d\\u062f\\u062b \\u063a\\u064a\\u0631 \\u0645\\u0633\\u0628\\u0648\\u0642 \\u064a\\u062c\\u0645\\u0639 \\u0627\\u0644\\u0645\\u0647\\u0646\\u062f\\u0633\\u064a\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0647\\u0646\\u064a\\u064a\\u0646 \\u0641\\u064a \\u0635\\u0646\\u0627\\u0639\\u0629 \\u0627\\u0644\\u0628\\u0646\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0643\\u0645\\u064a\\u0627\\u062a \\u0627\\u0644\\u062d\\u0627\\u0635\\u0644\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0647\\u062f\\u0646\\u0629 \\u0645\\u0646 \\u0623\\u062c\\u0644 \\u0627\\u0644\\u062a\\u062d\\u062f\\u062b \\u0639\\u0646<br>\\u0623\\u062d\\u062f\\u062b \\u0627\\u0644\\u0645\\u0633\\u0627\\u0647\\u0645\\u0629. \\u0645\\u0639\\u0631\\u0636 \\u0644\\u062a\\u0643\\u0646\\u0648\\u0644\\u0648\\u062c\\u064a\\u0627 \\u0627\\u0644\\u0628\\u0646\\u0627\\u0621 \\u0648\\u0627\\u0644\\u062a\\u062c\\u062f\\u064a\\u062f \\u0647\\u0648 \\u0645\\u0646\\u0635\\u0629 \\u0641\\u0631\\u064a\\u062f\\u0629 \\u062a\\u0633\\u0645\\u062d \\u0644\\u0644\\u0646\\u0627\\u0634\\u0631\\u064a\\u0646 \\u0644\\u0625\\u0638\\u0647\\u0627\\u0631 \\u0623\\u062d\\u062f\\u062b \\u062a\\u0642\\u0646\\u064a\\u0627\\u062a\\u0647\\u0645 \\u0648\\u0627\\u0644\\u062a\\u0642\\u0627\\u0646\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u0637\\u0648\\u0631\\u0629<br>\\u060c \\u0641\\u064a \\u062d\\u064a\\u0646 \\u064a\\u0645\\u0643\\u0646 \\u0644\\u0644\\u0645\\u062a\\u0641\\u0631\\u062c\\u064a\\u0646 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u0643\\u064a\\u0646 \\u0641\\u064a \\u0627\\u0644\\u0645\\u0639\\u0631\\u0636 \\u0627\\u0643\\u062a\\u0634\\u0627\\u0641 \\u0623\\u062d\\u062f\\u062b \\u0627\\u0644\\u0623\\u0628\\u062d\\u0627\\u062b \\u0648\\u0627\\u0644\\u062a\\u0637\\u0648\\u0631\\u0627\\u062a \\u0641\\u064a \\u0645\\u062c\\u0627\\u0644 \\u0627\\u0644\\u062a\\u0643\\u0646\\u0648\\u0644\\u0648\\u062c\\u064a\\u0627. \\u062e\\u0644\\u0627\\u0644 \\u0630\\u0644\\u0643 \\u0627\\u0644\\u064a\\u0648\\u0645<br>\\u0627\\u0644\\u062a\\u0627\\u0631\\u064a\\u062e\\u064a\\u060c \\u0633\\u0646\\u0642\\u062f\\u0645 \\u0644\\u0643\\u0645 \\u0623\\u062d\\u062f\\u062b \\u0627\\u0644\\u0645\\u0633\\u0627\\u0647\\u0645\\u0629 \\u0627\\u0644\\u062a\\u064a \\u0633\\u062a\\u0648\\u0627\\u062c\\u0647 \\u0648\\u0627\\u062c\\u0647\\u0629 \\u0627\\u0644\\u0628\\u0646\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0643\\u0645\\u064a\\u0627\\u062a \\u0627\\u0644\\u062d\\u0627\\u0635\\u0644\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0647\\u062f\\u0646\\u0629 \\u0645\\u0646 \\u0623\\u062c\\u0644 \\u0645\\u0633\\u062a\\u0642\\u0628\\u0644 \\u0623\\u0637\\u0648\\u0644 \\u0648\\u0633\\u0628\\u062d\\u0627\\u0646.<\\/p>","terms":""}}',
                'created_at' => '2025-02-15 17:50:07',
                'updated_at' => '2025-02-15 17:50:07',
                'event' => 'creation',
                'batch_uuid' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'log_name' => 'event_announcements',
                'description' => 'Modification de l\'événement',
                'subject_type' => 'App\\Models\\EventAnnouncement',
                'subject_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"terms":{"fr":{"ancien":null,"nouveau":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.\\"<\\/li><\\/ul>"},"en":{"ancien":null,"nouveau":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.\\"<\\/li><\\/ul><p><br><\\/p>"},"ar":{"ancien":null,"nouveau":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.<\\/li><\\/ul><p><br><\\/p>"}}}',
                'created_at' => '2025-02-15 17:57:35',
                'updated_at' => '2025-02-15 17:57:35',
                'event' => 'modification',
                'batch_uuid' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'log_name' => 'event_announcements',
                'description' => 'Modification de l\'événement',
                'subject_type' => 'App\\Models\\EventAnnouncement',
                'subject_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"terms":{"ar":{"ancien":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.<\\/li><\\/ul><p><br><\\/p>","nouveau":"<h2 dir=\\"rtl\\">&nbsp;\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 DZ Build :<\\/h2><ul dir=\\"rtl\\"><li>\\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u0643\\u0629 \\u0641\\u064a DZ Build \\u0645\\u062c\\u0627\\u0646\\u064a\\u0629 \\u0648 \\u062e\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062a\\u0643\\u0644\\u0641\\u0629 \\u0644\\u0644\\u0645\\u0647\\u0646\\u064a\\u064a\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646.<\\/li><li>\\u0627\\u0644\\u0628\\u0627\\u0626\\u0639\\u064a\\u0646 \\u0645\\u0633\\u0626\\u0648\\u0644\\u0648\\u0646 \\u0639\\u0646 \\u062c\\u0648\\u062f\\u0629 \\u0648\\u0645\\u0648\\u062b\\u0648\\u0642\\u064a\\u0629 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0648\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0639\\u0631\\u0648\\u0636\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0646\\u062f\\u0648\\u0629.<\\/li><li>\\u0623\\u064a \\u062f\\u062e\\u0648\\u0644 \\u063a\\u064a\\u0631 \\u0645\\u0635\\u0631\\u062d \\u0628\\u0647 \\u0625\\u0644\\u0649 \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0627\\u0644\\u0639\\u0631\\u0636 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u0633\\u0627\\u062d\\u0627\\u062a \\u0627\\u0644\\u0645\\u062d\\u062c\\u0648\\u0632\\u0629 \\u0633\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0643\\u0623\\u0645\\u0631 \\u0645\\u062e\\u0627\\u0644\\u0641 \\u0644\\u0644\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0641\\u0627\\u062f\\u0629.<\\/li><\\/ul><ul><li>DZ Build \\u0644\\u0646 \\u062a\\u0643\\u0648\\u0646 \\u0645\\u0633\\u0624\\u0648\\u0644\\u0629 \\u0641\\u064a \\u062d\\u0627\\u0644 \\u062d\\u062f\\u0648\\u062b \\u0623\\u0636\\u0631\\u0627\\u0631 \\u0623\\u0648 \\u062e\\u0633\\u0627\\u0626\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0623\\u0648 \\u063a\\u064a\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0646\\u062a\\u064a\\u062c\\u0629 \\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a.<\\/li><\\/ul><p><br><\\/p>"}}}',
                'created_at' => '2025-02-15 17:58:17',
                'updated_at' => '2025-02-15 17:58:17',
                'event' => 'modification',
                'batch_uuid' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'log_name' => 'event_announcements',
                'description' => 'Modification de l\'événement',
                'subject_type' => 'App\\Models\\EventAnnouncement',
                'subject_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"terms":{"ar":{"ancien":"<h2 dir=\\"rtl\\">&nbsp;\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 DZ Build :<\\/h2><ul dir=\\"rtl\\"><li>\\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u0643\\u0629 \\u0641\\u064a DZ Build \\u0645\\u062c\\u0627\\u0646\\u064a\\u0629 \\u0648 \\u062e\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062a\\u0643\\u0644\\u0641\\u0629 \\u0644\\u0644\\u0645\\u0647\\u0646\\u064a\\u064a\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646.<\\/li><li>\\u0627\\u0644\\u0628\\u0627\\u0626\\u0639\\u064a\\u0646 \\u0645\\u0633\\u0626\\u0648\\u0644\\u0648\\u0646 \\u0639\\u0646 \\u062c\\u0648\\u062f\\u0629 \\u0648\\u0645\\u0648\\u062b\\u0648\\u0642\\u064a\\u0629 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0648\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0639\\u0631\\u0648\\u0636\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0646\\u062f\\u0648\\u0629.<\\/li><li>\\u0623\\u064a \\u062f\\u062e\\u0648\\u0644 \\u063a\\u064a\\u0631 \\u0645\\u0635\\u0631\\u062d \\u0628\\u0647 \\u0625\\u0644\\u0649 \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0627\\u0644\\u0639\\u0631\\u0636 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u0633\\u0627\\u062d\\u0627\\u062a \\u0627\\u0644\\u0645\\u062d\\u062c\\u0648\\u0632\\u0629 \\u0633\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0643\\u0623\\u0645\\u0631 \\u0645\\u062e\\u0627\\u0644\\u0641 \\u0644\\u0644\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0641\\u0627\\u062f\\u0629.<\\/li><\\/ul><ul><li>DZ Build \\u0644\\u0646 \\u062a\\u0643\\u0648\\u0646 \\u0645\\u0633\\u0624\\u0648\\u0644\\u0629 \\u0641\\u064a \\u062d\\u0627\\u0644 \\u062d\\u062f\\u0648\\u062b \\u0623\\u0636\\u0631\\u0627\\u0631 \\u0623\\u0648 \\u062e\\u0633\\u0627\\u0626\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0623\\u0648 \\u063a\\u064a\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0646\\u062a\\u064a\\u062c\\u0629 \\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a.<\\/li><\\/ul><p><br><\\/p>","nouveau":"<h2 dir=\\"rtl\\">&nbsp;\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 DZ Build :<\\/h2><ul dir=\\"rtl\\"><li>\\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u0643\\u0629 \\u0641\\u064a DZ Build \\u0645\\u062c\\u0627\\u0646\\u064a\\u0629 \\u0648 \\u062e\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062a\\u0643\\u0644\\u0641\\u0629 \\u0644\\u0644\\u0645\\u0647\\u0646\\u064a\\u064a\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646.<\\/li><li>\\u0627\\u0644\\u0628\\u0627\\u0626\\u0639\\u064a\\u0646 \\u0645\\u0633\\u0626\\u0648\\u0644\\u0648\\u0646 \\u0639\\u0646 \\u062c\\u0648\\u062f\\u0629 \\u0648\\u0645\\u0648\\u062b\\u0648\\u0642\\u064a\\u0629 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0648\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0639\\u0631\\u0648\\u0636\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0646\\u062f\\u0648\\u0629.<\\/li><li>\\u0623\\u064a \\u062f\\u062e\\u0648\\u0644 \\u063a\\u064a\\u0631 \\u0645\\u0635\\u0631\\u062d \\u0628\\u0647 \\u0625\\u0644\\u0649 \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0627\\u0644\\u0639\\u0631\\u0636 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u0633\\u0627\\u062d\\u0627\\u062a \\u0627\\u0644\\u0645\\u062d\\u062c\\u0648\\u0632\\u0629 \\u0633\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0643\\u0623\\u0645\\u0631 \\u0645\\u062e\\u0627\\u0644\\u0641 \\u0644\\u0644\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0641\\u0627\\u062f\\u0629.<\\/li><li>&nbsp;\\u0644\\u0646 \\u062a\\u0643\\u0648\\u0646 \\u0645\\u0633\\u0624\\u0648\\u0644\\u0629 \\u0641\\u064a \\u062d\\u0627\\u0644 \\u062d\\u062f\\u0648\\u062b \\u0623\\u0636\\u0631\\u0627\\u0631 \\u0623\\u0648 \\u062e\\u0633\\u0627\\u0626\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0623\\u0648 \\u063a\\u064a\\u0631 \\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0646\\u062a\\u064a\\u062c\\u0629 \\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0648\\u0642\\u0639 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a.<\\/li><\\/ul><p><br><\\/p>"}}}',
                'created_at' => '2025-02-15 17:58:28',
                'updated_at' => '2025-02-15 17:58:28',
                'event' => 'modification',
                'batch_uuid' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'log_name' => 'event_announcements',
                'description' => 'Modification de l\'événement',
                'subject_type' => 'App\\Models\\EventAnnouncement',
                'subject_id' => 1,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"terms":{"fr":{"ancien":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.\\"<\\/li><\\/ul>","nouveau":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du site web.<\\/li><\\/ul>"},"en":{"ancien":"<h2>Conditions d\'utilisation du DZ Build :<\\/h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.<\\/li><li>Les exposants sont responsables de la qualit\\u00e9 et de la s\\u00e9curit\\u00e9 de leurs produits et services pr\\u00e9sent\\u00e9s sur le<\\/li><li>salon.<\\/li><li>Tout acc\\u00e8s non autoris\\u00e9 \\u00e0 la zone d\'exposition ou aux espaces r\\u00e9serv\\u00e9s sera consid\\u00e9r\\u00e9 comme une violation des<\\/li><li>conditions d\'utilisation.<\\/li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects r\\u00e9sultant de l\'utilisation du<\\/li><li>site web.\\"<\\/li><\\/ul><p><br><\\/p>","nouveau":"<h2>Terms of Use for DZ Build :<\\/h2><ul><li>Participation in DZ Build is free and open to industry professionals.<\\/li><li>Exhibitors are responsible for the quality and safety of their products and services presented on the expo floor.<\\/li><li>Any unauthorized access to the exhibition area or reserved spaces will be considered a breach of these terms of use.<\\/li><li>DZ Build will not be held liable for direct or indirect damages or losses resulting from the use of this website.<\\/li><\\/ul><p><br><\\/p>"}}}',
                'created_at' => '2025-02-15 18:00:00',
                'updated_at' => '2025-02-15 18:00:00',
                'event' => 'modification',
                'batch_uuid' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'log_name' => 'authentication',
                'description' => 'User logged in',
                'subject_type' => NULL,
                'subject_id' => NULL,
                'causer_type' => 'App\\Models\\User',
                'causer_id' => 1,
                'properties' => '{"email":"admin@admin.dev","name":"Admin"}',
                'created_at' => '2025-02-15 18:04:04',
                'updated_at' => '2025-02-15 18:04:04',
                'event' => 'login',
                'batch_uuid' => NULL,
            ),
        ));
        
        
    }
}