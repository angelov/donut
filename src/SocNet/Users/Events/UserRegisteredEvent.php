<?php

namespace SocNet\Users\Events;

use JMS\Serializer\Annotation as Serializer;
use SocNet\Users\User;

class UserRegisteredEvent
{
    /**
     * @Serializer\Type(name="SocNet\Users\User")
     */
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
