<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Users\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Users\User;

class DoctrineUsersRepository implements UsersRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function store(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function findByEmail(string $email): User
    {
        $found = $this->getRepository()->findOneBy(['email' => $email]);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function find(string $id): User
    {
        $found = $this->getRepository()->find($id);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    private function getRepository() : ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    public function all(): array
    {
        return $this->getRepository()->findAll();
    }
}
