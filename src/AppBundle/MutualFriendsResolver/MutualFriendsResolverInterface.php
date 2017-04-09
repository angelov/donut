<?php

namespace AppBundle\MutualFriendsResolver;

use SocNet\Users\User;

interface MutualFriendsResolverInterface
{
    /**
     * @return \SocNet\Users\User[]
     */
    public function forUsers(User $first, User $second) : array;
}
