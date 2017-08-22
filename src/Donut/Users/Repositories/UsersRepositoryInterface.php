<?php

namespace Angelov\Donut\Users\Repositories;

use Angelov\Donut\Users\User;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;

interface UsersRepositoryInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function find(string $id) : User;

    /**
     * @return User[]
     */
    public function all() : array;

    /**
     * @throws ResourceNotFoundException
     */
    public function findByEmail(string $email) : User;

    public function store(User $user) : void;
}
