<?php

namespace Angelov\Donut\Friendships\FriendshipRequests\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;

interface FriendshipRequestsRepositoryInterface
{
    public function store(FriendshipRequest $request) : void;

    public function destroy(FriendshipRequest $request) : void;

    /**
     * @throws ResourceNotFoundException
     */
    public function find(string $id) : FriendshipRequest;
}
