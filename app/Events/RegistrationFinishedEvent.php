<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Module\User\Models\User;

class RegistrationFinishedEvent
{
    use SerializesModels;

    public function __construct(public User $user)
    {
    }
}
