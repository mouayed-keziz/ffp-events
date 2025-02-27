<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedNotificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('notifications')->delete();
        
        \DB::table('notifications')->insert(array (
            0 => 
            array (
                'id' => 'fdc6e210-c6f4-429f-b7ea-f93080f86daa',
                'type' => 'Filament\\Notifications\\DatabaseNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1,
                'data' => '{"actions":[{"name":"goto-dashboard","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":null,"iconPosition":"before","iconSize":null,"isOutlined":false,"isDisabled":false,"label":"Go to dashboard","shouldClose":false,"shouldMarkAsRead":false,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":false,"size":"sm","tooltip":null,"url":"\\/admin","view":"filament-actions::link-action"},{"name":"mark-as-read","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":"heroicon-o-eye","iconPosition":"before","iconSize":null,"isOutlined":true,"isDisabled":false,"label":"Mark as read","shouldClose":false,"shouldMarkAsRead":true,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":false,"size":"sm","tooltip":null,"url":null,"view":"filament-actions::button-action"}],"body":"Welcome to FFP Events, you are now an admin.","color":null,"duration":"persistent","icon":null,"iconColor":null,"status":null,"title":"Welcome to FFP Events","view":"filament-notifications::notification","viewData":[],"format":"filament"}',
                'read_at' => NULL,
                'created_at' => '2025-02-15 17:47:59',
                'updated_at' => '2025-02-15 17:47:59',
            ),
            1 => 
            array (
                'id' => 'b6cb3e83-fb0d-473f-a90f-1a0f919053ca',
                'type' => 'Filament\\Notifications\\DatabaseNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1,
                'data' => '{"actions":[{"name":"download_csv","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":null,"iconPosition":"before","iconSize":null,"isOutlined":false,"isDisabled":false,"label":"T\\u00e9l\\u00e9charger .csv","shouldClose":false,"shouldMarkAsRead":true,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":true,"size":"sm","tooltip":null,"url":"\\/filament\\/exports\\/1\\/download?format=csv","view":"filament-actions::link-action"},{"name":"download_xlsx","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":null,"iconPosition":"before","iconSize":null,"isOutlined":false,"isDisabled":false,"label":"T\\u00e9l\\u00e9charger .xlsx","shouldClose":false,"shouldMarkAsRead":true,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":true,"size":"sm","tooltip":null,"url":"\\/filament\\/exports\\/1\\/download?format=xlsx","view":"filament-actions::link-action"}],"body":"Your log export has completed and 12 rows exported.","color":null,"duration":"persistent","icon":"heroicon-o-check-circle","iconColor":"success","status":"success","title":"Exportation termin\\u00e9e","view":"filament-notifications::notification","viewData":[],"format":"filament"}',
                'read_at' => NULL,
                'created_at' => '2025-02-16 14:26:43',
                'updated_at' => '2025-02-16 14:26:43',
            ),
            2 => 
            array (
                'id' => '36b5fc25-ada4-4f43-a46f-5f48dfe2b53d',
                'type' => 'Filament\\Notifications\\DatabaseNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1,
                'data' => '{"actions":[{"name":"download_csv","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":null,"iconPosition":"before","iconSize":null,"isOutlined":false,"isDisabled":false,"label":"T\\u00e9l\\u00e9charger .csv","shouldClose":false,"shouldMarkAsRead":true,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":true,"size":"sm","tooltip":null,"url":"\\/filament\\/exports\\/1\\/download?format=csv","view":"filament-actions::link-action"},{"name":"download_xlsx","color":null,"event":null,"eventData":[],"dispatchDirection":false,"dispatchToComponent":null,"extraAttributes":[],"icon":null,"iconPosition":"before","iconSize":null,"isOutlined":false,"isDisabled":false,"label":"T\\u00e9l\\u00e9charger .xlsx","shouldClose":false,"shouldMarkAsRead":true,"shouldMarkAsUnread":false,"shouldOpenUrlInNewTab":true,"size":"sm","tooltip":null,"url":"\\/filament\\/exports\\/1\\/download?format=xlsx","view":"filament-actions::link-action"}],"body":"Your log export has completed and 25 rows exported.","color":null,"duration":"persistent","icon":"heroicon-o-check-circle","iconColor":"success","status":"success","title":"Exportation termin\\u00e9e","view":"filament-notifications::notification","viewData":[],"format":"filament"}',
                'read_at' => NULL,
                'created_at' => '2025-02-23 17:41:29',
                'updated_at' => '2025-02-23 17:41:29',
            ),
        ));
        
        
    }
}