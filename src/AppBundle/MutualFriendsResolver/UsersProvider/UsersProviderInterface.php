<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;
use SocNet\Core\Exceptions\ResourceNotFoundException;

interface UsersProviderInterface
{
    /**
     * @throws \SocNet\Core\Exceptions\ResourceNotFoundException
     */
    public function getById(int $id) : User;
}
