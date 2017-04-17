<?php

namespace SocNet\Friendships\FriendshipRequests\Repositories;

use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

interface FriendshipRequestsRepositoryInterface
{
    public function store(FriendshipRequest $request) : void;
}
