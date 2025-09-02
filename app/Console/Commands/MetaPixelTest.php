<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MetaPixelTest extends Command
{
    protected $signature = 'app:meta-pixel-test';
    protected $description = 'Send a CompleteRegistration event to Meta Pixel (with optional test_event_code)';

    public function handle()
    {
        $pixelId = config('meta_pixel.pixel_id');
        $accessToken = config('meta_pixel.access_token');

        $payload = [
            'data' => [[
                'event_name' => 'Inscription Visiteur',
                'event_time' => time(),
                'action_source' => 'website',
                'user_data' => [
                    'em' => hash('sha256', strtolower('m_keziz3333@estin.dz')),
                    'ph' => hash('sha256', '9876543210'),
                    'fn' => hash('sha256', strtolower('Mouayed')),
                    'ln' => hash('sha256', strtolower('KEZIZ')),
                    'client_ip_address' => '192.168.1.1',
                ],
            ]],
            'access_token' => $accessToken,
        ];

        if (true) {
            $payload['test_event_code'] = "TEST64146";
        }

        $response = Http::post(
            "https://graph.facebook.com/v18.0/{$pixelId}/events",
            $payload
        );

        if ($response->successful()) {
            $this->info('Response: ' . $response->body());
        } else {
            $this->error('Error occurred: HTTP ' . $response->status());
            $this->error('Response: ' . $response->body());
        }
    }
}
