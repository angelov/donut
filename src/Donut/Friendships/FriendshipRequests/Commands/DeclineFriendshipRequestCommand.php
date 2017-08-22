<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

class DeclineFriendshipRequestCommand
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
