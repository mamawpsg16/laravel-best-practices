<?php

namespace App\Listeners;

use App\Events\ResetPassword;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ResetPassword $event): void
    {
        Password::sendResetLink($event->user);
        
    }
}
