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
        $faker = Faker::create('ar_SA');

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.dev',
            'password' => bcrypt('adminadmin'),
        ]);

        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $admin->assignRole($role);

        $arabicTitles = [
            'مرحباً بك في النظام',
            'تم تحديث البيانات بنجاح',
            'إشعار جديد',
            'تنبيه هام',
            'معلومات النظام',
            'تحديث الحالة'
        ];

        $arabicBodies = [
            'نرحب بك في نظام إدارة المحتوى الخاص بنا. نتمنى لك تجربة ممتعة.',
            'تم تحديث بيانات النظام بنجاح. يرجى مراجعة التغييرات.',
            'لديك إشعار جديد يحتاج إلى مراجعة.',
            'يرجى الانتباه إلى هذا التنبيه الهام.',
            'تم تحديث معلومات النظام. يرجى الاطلاع على التفاصيل.',
            'تم تغيير حالة النظام. يرجى التحقق من التحديثات.'
        ];

        foreach (range(1, 6) as $index) {
            $isSuccess = $index % 2 === 0;

            $notification = Notification::make()
                ->title($arabicTitles[$index - 1])
                ->body($arabicBodies[$index - 1])
                ->actions([
                    // Action::make('عرض')
                    //     ->button()
                    //     ->color($isSuccess ? 'success' : 'danger')
                    //     ->markAsRead(),
                    // Action::make('وضع كغير مقروء')
                    //     ->button()
                    //     ->color($isSuccess ? 'success' : 'danger')
                    //     ->markAsUnread()
                    //     ->tooltip("وضع كغير مقروء"),
                    // Action::make('اختبار')
                    //     ->button()
                    //     ->color($isSuccess ? 'success' : 'danger')
                    //     ->action(function () {
                    //         throw new \Exception('مرحبا بالعالم');
                    //     }),
                ])
                ->toDatabase();

            $admin->notify($notification);
        }
        User::factory(500)->create();
    }
}
