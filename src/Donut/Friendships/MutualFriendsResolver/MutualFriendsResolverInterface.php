<?php

namespace Angelov\Donut\Friendships\MutualFriendsResolver;

use Angelov\Donut\Users\User;

interface MutualFriendsResolverInterface
{
    /**
     * @return User[]
     */
    public function forUsers(User $first, User $second) : array;
}
