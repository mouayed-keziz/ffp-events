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
        $this->call('iseed', [
            'tables' => 'users,categories,event_announcements,articles,article_category,activity_log,roles,permissions,model_has_permissions,model_has_roles,role_has_permissions,notifications,settings,visitor_forms,products,exhibitor_forms,exhibitors,visitors,visitor_submissions,exhibitor_submissions,plan_tiers,plans',
            '--clean' => true,
            '--force' => true,
            '--classnameprefix' => 'Iseed', // all generated classes will share this prefix
        ]);
    }
}
