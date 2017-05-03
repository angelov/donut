<?php

namespace SocNet\Friendships\Commands;

use SocNet\Users\User;

class StoreFriendshipCommand
{
    private $id;
    private $user;
    private $friend;

    public function __construct(string $id, User $user, User $friend)
    {
        $this->id = $id;
        $this->user = $user;
        $this->friend = $friend;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function getFriend() : User
    {
        return $this->friend;
    }
}
