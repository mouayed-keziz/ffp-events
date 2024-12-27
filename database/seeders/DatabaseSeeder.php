<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\application\RoleSeeder;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Faker\Factory as Faker;
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
            RoleSeeder::class,
            AdvancedEventAnnouncementSeeder::class,
        ]);

        // Create admin and other users
        User::factory()->superAdmin()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => bcrypt('adminadmin'),
        ]);

        User::factory(16)->exhibitor()->create();
        User::factory(4)->admin()->create();
        User::factory(count: 50)->visitor()->create();

        // Create categories

        $categories = Category::factory(5)->create();

        // Create articles and attach random categories
        Article::factory(15)->published()->create()->each(function ($article) use ($categories) {
            $article->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        Article::factory(5)->unpublished()->create()->each(function ($article) use ($categories) {
            $article->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
