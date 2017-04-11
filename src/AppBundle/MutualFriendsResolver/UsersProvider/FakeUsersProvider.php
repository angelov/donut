<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;
use SocNet\Core\Exceptions\ResourceNotFoundException;

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
