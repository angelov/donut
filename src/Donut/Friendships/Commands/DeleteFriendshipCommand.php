<?php

namespace Angelov\Donut\Friendships\Commands;

use Angelov\Donut\Friendships\Friendship;

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
