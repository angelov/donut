<?php

namespace SocNet\Friendships\MutualFriendsResolver\UsersProvider;

use SocNet\Users\User;
use SocNet\Users\Repositories\UsersRepositoryInterface;

class RepositoryUsersProvider implements UsersProviderInterface
{
    private $repository;

    public function __construct(UsersRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getById(string $id): User
    {
        return $this->repository->find($id);
    }
}
