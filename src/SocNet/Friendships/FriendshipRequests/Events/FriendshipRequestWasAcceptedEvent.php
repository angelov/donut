<?php

namespace SocNet\Friendships\FriendshipRequests\Events;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class FriendshipRequestWasAcceptedEvent
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
