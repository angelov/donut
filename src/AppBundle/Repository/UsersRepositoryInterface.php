<?php

namespace AppBundle\Repository;

use SocNet\Users\User;
use AppBundle\Exceptions\ResourceNotFoundException;

interface UsersRepositoryInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function find(int $id) : User;

    /**
     * @return \SocNet\Users\User[]
     */
    public function all() : array;

    /**
     * @throws ResourceNotFoundException
     */
    public function getByEmail(string $email) : User;
}
