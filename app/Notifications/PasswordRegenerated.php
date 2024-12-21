<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordRegenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Password Has Been Reset')
            ->view(
                'mails.regenerate-password',
                [
                    'name' => $notifiable->name,
                    'email' => $notifiable->email,
                    'password' => $this->password,
                ]
            );
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
