<?php

namespace SocNet\Friendships\FriendshipRequests\Commands;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class AcceptFriendshipRequestCommand
{
    private $request;

    public function __construct(FriendshipRequest $request)
    {
        $this->request = $request;
    }

    public function getFriendshipRequest() : FriendshipRequest
    {
        return $this->request;
    }
}
