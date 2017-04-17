<?php

namespace SocNet\Friendships\FriendshipRequests\Commands;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class CancelFriendshipRequestCommand
{
    private $friendshipRequest;

    public function __construct(FriendshipRequest $friendshipRequest)
    {
        $this->friendshipRequest = $friendshipRequest;
    }

    public function getFriendshipRequest() : FriendshipRequest
    {
        return $this->friendshipRequest;
    }
}
