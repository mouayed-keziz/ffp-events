<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();


        $faker = Faker::create();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => bcrypt('adminadmin'),
        ]);

        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $admin->assignRole($role);

        foreach (range(1, 6) as $index) {
            $admin->notify(
                Notification::make()
                    ->title($faker->sentence)
                    ->body($faker->paragraph)
                    ->success()
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->markAsRead(),
                        Action::make('markAsUnread')
                            ->button()
                            ->markAsUnread()
                            ->tooltip("Mark as unread"),
                        Action::make('ddHelloWorld')
                            ->button()
                            ->action(fn() => dd('Hello World')),
                    ])
                    ->toDatabase()
            );
        }
    }
}
