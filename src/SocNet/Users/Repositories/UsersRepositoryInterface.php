<?php

namespace SocNet\Users\Repositories;

use SocNet\Users\User;
use AppBundle\Exceptions\ResourceNotFoundException;

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
