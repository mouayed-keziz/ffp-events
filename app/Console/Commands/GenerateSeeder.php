<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iseed:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate seeders for all app tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Core application tables with actual data (excluding logs and temporary data)
        $tables = [
            // User management
            'users',
            'roles',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',

            // Content management
            'categories',
            'articles',
            'article_category',
            'banners',
            'event_announcements',
            'event_announcement_user',

            // Events and forms
            'visitor_forms',
            'visitor_submissions',
            'visitors',
            'exhibitor_forms',
            'exhibitor_submissions',
            'exhibitors',
            'exhibitor_payment_slices',
            'exhibitor_post_payment_forms',

            // Products and plans
            'products',
            'plan_tiers',
            'plans',

            // Badges and attendance
            'badges',
            'current_attendees',

            // Media and uploads
            'media',

            // App settings
            'settings',
            'notifications',

            // Import/Export (if they contain actual data, not logs)
            'exports',
            'imports',
            'failed_import_rows',

            // Social features
            'shares',
        ];

        $this->call('iseed', [
            'tables' => implode(',', $tables),
            '--clean' => true,
            '--force' => true,
            '--classnameprefix' => 'BACKUP', // all generated classes will share this prefix
        ]);

        $this->info('Excluded from seeding:');
        $this->line('- activity_log (contains runtime logs)');
        $this->line('- telescope_* (debugging data)');
        $this->line('- laravisits (tracking data)');
        $this->line('- badge_check_logs (runtime logs)');
    }
}
