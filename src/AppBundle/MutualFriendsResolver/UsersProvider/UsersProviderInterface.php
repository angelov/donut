<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use AppBundle\Entity\User;
use AppBundle\Exceptions\ResourceNotFoundException;

interface UsersProviderInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function getById(int $id) : User;
}
