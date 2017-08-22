<?php

namespace Angelov\Donut\Friendships\Repositories;

use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Users\User;

interface FriendshipsRepositoryInterface
{
    public function store(Friendship $friendship) : void;

    public function destroy(Friendship $friendship) : void;

    /**
     * @return Friendship[]
     */
    public function findBetweenUsers(User $first, User $second) : array;
}
