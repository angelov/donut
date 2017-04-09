<?php

namespace SocNet\Users\Repositories;

use SocNet\Users\User;
use AppBundle\Exceptions\ResourceNotFoundException;

interface UsersRepositoryInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function find(int $id) : User;

    /**
     * @return User[]
     */
    public function all() : array;

    /**
     * @throws ResourceNotFoundException
     */
    public function getByEmail(string $email) : User;

    public function store(User $user) : void;
}
