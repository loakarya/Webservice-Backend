<?php

namespace App\Listeners;

use App\Events\EmployeeRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmployeeRegistered as EmployeeRegisteredNotification;

class SendEmployeeGreeting implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmployeeRegistered  $event
     * @return void
     */
    public function handle(EmployeeRegistered $event)
    {
        $event->user->notify(new EmployeeRegisteredNotification($event->user));
    }
}
