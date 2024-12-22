<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('default.name', env("APP_NAME", "ffp events"));
        $this->migrator->add('default.email', 'contact@ffpevents.dz');
        $this->migrator->add('default.phone', '+213555555555');
        $this->migrator->add('default.address', '123 Rue Didouche Mourad');
        $this->migrator->add('default.city', 'Alger');
        $this->migrator->add('default.state', 'Alger');
        $this->migrator->add('default.zip', '16000');
        $this->migrator->add('default.country', 'Algeria');
    }
};
