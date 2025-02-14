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
        $this->migrator->add('default.faq', [
            [
                'question' => 'What is FFP Events?',
                'answer' => 'FFP Events is a platform that allows you to create and manage events.'
            ],
            [
                'question' => 'How can I create an event?',
                'answer' => 'You can create an event by clicking on the "Create Event" button in the dashboard.'
            ],
            [
                'question' => 'How can I manage my events?',
                'answer' => 'You can manage your events by going to the "My Events" section in the dashboard.'
            ],
            [
                'question' => 'How can I buy tickets for an event?',
                'answer' => 'You can buy tickets for an event by clicking on the "Buy Tickets" button on the event page.'
            ],
            [
                'question' => 'How can I contact FFP Events?',
                'answer' => 'You can contact FFP Events by sending an email to'
            ],
        ]);
    }
};
