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


        // User::factory(16)->exhibitor()->create();
        // User::factory(4)->admin()->create();
        // User::factory(count: 50)->visitor()->create();

        // Create categories

        // $categories = Category::factory(5)->create();

        // // Create articles and attach random categories
        // Article::factory(15)->published()->create()->each(function ($article) use ($categories) {
        //     $article->categories()->attach(
        //         $categories->random(rand(1, 3))->pluck('id')->toArray()
        //     );
        //     $this->call(UsersTableSeeder::class);

        // Article::factory(5)->unpublished()->create()->each(function ($article) use ($categories) {
        //     $article->categories()->attach(
        //         $categories->random(rand(1, 3))->pluck('id')->toArray()
        //     );




        $this->call(IseedUsersTableSeeder::class);

        $this->call(IseedRolesTableSeeder::class);
        $this->call(IseedPermissionsTableSeeder::class);
        $this->call(IseedModelHasPermissionsTableSeeder::class);
        $this->call(IseedModelHasRolesTableSeeder::class);
        $this->call(IseedRoleHasPermissionsTableSeeder::class);
        $this->call(IseedNotificationsTableSeeder::class);

        $this->call(IseedEventAnnouncementsTableSeeder::class);
        $this->call(IseedVisitorFormsTableSeeder::class);

        $this->call(IseedCategoriesTableSeeder::class);
        $this->call(IseedArticlesTableSeeder::class);
        $this->call(IseedArticleCategoryTableSeeder::class);

        $this->call(IseedActivityLogTableSeeder::class);



        $this->call(IseedSettingsTableSeeder::class);
    }
}
