<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;

class FakeUsersProvider implements UsersProviderInterface
{
    public function getById(int $id): User
    {
        return new User('Fake User', 'fake@example.com', '123456');
    }
}
