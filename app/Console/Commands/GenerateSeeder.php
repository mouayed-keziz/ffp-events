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
            'tables' => 'activity_log,article_category,articles,badge_check_logs,badges,banners,categories,current_attendees,event_announcement_user,event_announcements,exhibitor_forms,exhibitor_payment_slices,exhibitor_post_payment_forms,exhibitor_submissions,exhibitors,exports,failed_import_rows,imports,laravisits,media,model_has_permissions,model_has_roles,notifications,permissions,plan_tiers,plans,products,role_has_permissions,roles,settings,shares,telescope_entries,telescope_entries_tags,telescope_monitoring,users,visitor_forms,visitor_submissions,visitors',
            '--clean' => true,
            '--force' => true,
            '--classnameprefix' => 'BACKUP', // all generated classes will share this prefix
        ]);
    }
}
