<?php

namespace SocNet\Friendships\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;
use SocNet\Core\Exceptions\ResourceNotFoundException;

interface UsersProviderInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function getById(int $id) : User;
}
