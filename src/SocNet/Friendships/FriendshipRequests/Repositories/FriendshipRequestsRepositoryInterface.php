<?php

namespace SocNet\Friendships\FriendshipRequests\Repositories;

use SocNet\Core\Exceptions\ResourceNotFoundException;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

interface FriendshipRequestsRepositoryInterface
{
    public function store(FriendshipRequest $request) : void;

    public function destroy(FriendshipRequest $request) : void;

    /**
     * @throws ResourceNotFoundException
     */
    public function find(string $id) : FriendshipRequest;
}
