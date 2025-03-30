<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\application\RoleSeeder;
// use Faker\Factory as Faker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RoleSeeder::class,
            // AdminSeeder::class,
            // EventAnnouncementSeeder::class,
        ]);


        $this->call(IseedUsersTableSeeder::class);
        // $this->call(IseedExhibitorsTableSeeder::class);
        // $this->call(IseedVisitorsTableSeeder::class);

        $this->call(IseedRolesTableSeeder::class);
        $this->call(IseedPermissionsTableSeeder::class);
        $this->call(IseedModelHasPermissionsTableSeeder::class);
        $this->call(IseedModelHasRolesTableSeeder::class);
        $this->call(IseedRoleHasPermissionsTableSeeder::class);
        // $this->call(IseedNotificationsTableSeeder::class);

        // $this->call(IseedEventAnnouncementsTableSeeder::class);
        // $this->call(IseedVisitorFormsTableSeeder::class);
        // $this->call(IseedProductsTableSeeder::class);
        // $this->call(IseedExhibitorFormsTableSeeder::class);

        // $this->call(IseedCategoriesTableSeeder::class);
        // $this->call(IseedArticlesTableSeeder::class);
        // $this->call(IseedArticleCategoryTableSeeder::class);

        // $this->call(IseedActivityLogTableSeeder::class);

        // $this->call(IseedSettingsTableSeeder::class);
        // $this->call(IseedVisitorSubmissionsTableSeeder::class);
        // $this->call(IseedExhibitorSubmissionsTableSeeder::class);
    }
}
