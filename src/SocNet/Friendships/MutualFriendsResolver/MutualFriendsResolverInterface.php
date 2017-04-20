<?php

namespace SocNet\Friendships\MutualFriendsResolver;

use SocNet\Users\User;

interface MutualFriendsResolverInterface
{
    /**
     * @return User[]
     */
    public function forUsers(User $first, User $second) : array;
}
