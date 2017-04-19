<?php

namespace SocNet\Friendships\Repositories;

use SocNet\Friendships\Friendship;

interface FriendshipsRepositoryInterface
{
    public function store(Friendship $friendship) : void;

    public function destroy(Friendship $friendship) : void;
}
