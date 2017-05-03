<?php

namespace SocNet\Friendships\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;
use SocNet\Core\Exceptions\ResourceNotFoundException;

interface UsersProviderInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function getById(string $id) : User;
}
