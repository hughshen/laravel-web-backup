<?php

namespace App\Listeners;

use App\Events\UserLogin;

class UserLoginedInfo
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
     * @param  UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        if (!empty($event->user->id)) {
            $event->user->login_ip = ip2long(request()->getClientIp());
            $event->user->login_time = time();
            $event->user->save();
        }
    }
}
