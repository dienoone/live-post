<?php

namespace App\Subscribers\Models;

use App\Events\Models\User\UserCreated;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, SendWelcomeEmail::class);
    }
}
