<?php

namespace Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider;

use Angelov\Donut\Users\User;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;

interface UsersProviderInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function getById(string $id) : User;
}
