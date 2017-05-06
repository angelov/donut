<?php

namespace SocNet\Friendships\Events;

use JMS\Serializer\Annotation as Serializer;
use SocNet\Friendships\Friendship;

class FriendshipWasDeletedEvent
{
    /**
     * @Serializer\Type(name="SocNet\Friendships\Friendship")
     */
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
