<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->superAdmin()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => bcrypt('adminadmin'),
        ]);
        $user->notify(
            Notification::make()
                ->title('Welcome to FFP Events')
                ->body("Welcome to FFP Events, you are now an admin.")
                ->actions([
                    Action::make('goto-dashboard')
                        ->label("Go to dashboard")
                        ->url('/admin'),
                    Action::make("mark-as-read")
                        ->label("Mark as read")
                        ->markAsRead()
                        ->outlined()
                        ->icon("heroicon-o-eye")
                        ->button()
                ])
                ->toDatabase()
        );
    }
}
