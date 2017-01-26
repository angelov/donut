<?php

namespace AppBundle\MutualFriendsResolver;

use AppBundle\Entity\User;

interface MutualFriendsResolverInterface
{
    /**
     * @return User[]
     */
    public function forUsers(User $first, User $second) : array;
}
