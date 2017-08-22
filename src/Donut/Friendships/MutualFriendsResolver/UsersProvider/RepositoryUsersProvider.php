<?php

namespace Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider;

use Angelov\Donut\Users\User;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

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
