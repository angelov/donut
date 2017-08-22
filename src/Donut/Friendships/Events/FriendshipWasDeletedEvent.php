<?php

namespace Angelov\Donut\Friendships\Events;

use JMS\Serializer\Annotation as Serializer;
use Angelov\Donut\Friendships\Friendship;

class FriendshipWasDeletedEvent
{
    /**
     * @Serializer\Type(name="Angelov\Donut\Friendships\Friendship")
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
