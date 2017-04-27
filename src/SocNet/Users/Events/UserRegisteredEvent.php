<?php

namespace SocNet\Users\Events;

use SocNet\Users\User;

class UserRegisteredEvent
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser() : User
    {
        return $this->user;
    }
}
