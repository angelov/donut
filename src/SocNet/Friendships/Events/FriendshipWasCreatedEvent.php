<?php

namespace SocNet\Friendships\Events;

use SocNet\Friendships\Friendship;

class FriendshipWasCreatedEvent
{
    private $friendship;

    public function __construct(Friendship $friendship)
    {
        $this->friendship = $friendship;
    }

    public function getFriendship() : Friendship
    {
        return $this->friendship;
    }
}
