<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Add the new fields with default values
        $this->migrator->add('default.applicationTerms', '<p>These are the default application terms and conditions.</p>');
        $this->migrator->add('default.facebookLink', 'https://www.facebook.com/ffpevents');
        $this->migrator->add('default.linkedinLink', 'https://www.linkedin.com/company/ffpevents');
        $this->migrator->add('default.instagramLink', 'https://www.instagram.com/ffpevents');
    }
};
