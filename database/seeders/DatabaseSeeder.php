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

        $this->call(BACKUPRolesTableSeeder::class);
        $this->call(BACKUPPermissionsTableSeeder::class);
        $this->call(BACKUPModelHasRolesTableSeeder::class);
        $this->call(BACKUPModelHasPermissionsTableSeeder::class);
        $this->call(BACKUPRoleHasPermissionsTableSeeder::class);
        $this->call(BACKUPUsersTableSeeder::class);

        $this->call(BACKUPCategoriesTableSeeder::class);
        $this->call(BACKUPProductsTableSeeder::class);
        $this->call(BACKUPPlanTiersTableSeeder::class);
        $this->call(BACKUPPlansTableSeeder::class);
        $this->call(BACKUPSettingsTableSeeder::class);
        $this->call(BACKUPBannersTableSeeder::class);


        $this->call(BACKUPExhibitorsTableSeeder::class);
        $this->call(BACKUPVisitorsTableSeeder::class);
        $this->call(BACKUPEventAnnouncementsTableSeeder::class);

        $this->call(BACKUPExhibitorFormsTableSeeder::class);
        $this->call(BACKUPExhibitorSubmissionsTableSeeder::class);
        $this->call(BACKUPExhibitorPaymentSlicesTableSeeder::class);
        $this->call(BACKUPExhibitorPostPaymentFormsTableSeeder::class);
        $this->call(BACKUPVisitorFormsTableSeeder::class);
        $this->call(BACKUPVisitorSubmissionsTableSeeder::class);


        $this->call(BACKUPEventAnnouncementUserTableSeeder::class);
        $this->call(BACKUPArticlesTableSeeder::class);
        $this->call(BACKUPArticleCategoryTableSeeder::class);

        $this->call(BACKUPMediaTableSeeder::class);
        $this->call(BACKUPExportsTableSeeder::class);
        $this->call(BACKUPImportsTableSeeder::class);
        $this->call(BACKUPFailedImportRowsTableSeeder::class);

        $this->call(BACKUPNotificationsTableSeeder::class);
        $this->call(BACKUPSharesTableSeeder::class);
        $this->call(BACKUPActivityLogTableSeeder::class);
        $this->call(BACKUPLaravisitsTableSeeder::class);

        $this->call(BACKUPBadgesTableSeeder::class);
        $this->call(BACKUPCurrentAttendeesTableSeeder::class);
        $this->call(BACKUPBadgeCheckLogsTableSeeder::class);
    }
}
