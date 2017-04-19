<?php

namespace SocNet\Friendships\Repositories;

use SocNet\Friendships\Friendship;
use SocNet\Users\User;

interface FriendshipsRepositoryInterface
{
    public function store(Friendship $friendship) : void;

    public function destroy(Friendship $friendship) : void;

    /**
     * @return Friendship[]
     */
    public function findBetweenUsers(User $first, User $second) : array;
}
