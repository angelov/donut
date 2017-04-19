<?php

namespace SocNet\Friendships\Commands;

use SocNet\Friendships\Friendship;

class DeleteFriendshipCommand
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
