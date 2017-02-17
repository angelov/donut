<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use AppBundle\Entity\User;
use AppBundle\Exceptions\ResourceNotFoundException;

class FakeUsersProvider implements UsersProviderInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function getById(int $id): User
    {
        $user = new User();
        $user->setName('Fake User');
        $user->setEmail('fake@example.com');

        return $user;
    }
}
