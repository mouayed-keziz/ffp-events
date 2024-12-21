<?php

namespace Database\Seeders;

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

        User::factory()->superAdmin()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => bcrypt('adminadmin'),
        ]);

        User::factory(16)->exhibitor()->create();
        User::factory(4)->admin()->create();
        User::factory(count: 852)->visitor()->create();
    }
}
