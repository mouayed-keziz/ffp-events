<?php

namespace App\Console\Commands;

use App\Actions\UserActions;
use App\Models\User;
use Illuminate\Console\Command;

class action_test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'testing only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserActions::regeneratePassword(User::find(1));
    }
}
