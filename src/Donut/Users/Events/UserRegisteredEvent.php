<?php

namespace Angelov\Donut\Users\Events;

use JMS\Serializer\Annotation as Serializer;
use Angelov\Donut\Users\User;

class UserRegisteredEvent
{
    /**
     * @Serializer\Type(name="Angelov\Donut\Users\User")
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
