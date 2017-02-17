<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
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
}
