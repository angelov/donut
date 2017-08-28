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

namespace Angelov\Donut\Friendships\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Users\User;

class DoctrineFriendshipsRepository implements FriendshipsRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id) : Friendship
    {
        $found = $this->entityManager->find(Friendship::class, $id);

        if ($found) {
            return $found;
        }

        throw new ResourceNotFoundException();
    }

    public function store(Friendship $friendship): void
    {
        $this->entityManager->persist($friendship);
        $this->entityManager->flush();
    }

    public function destroy(Friendship $friendship): void
    {
        $this->entityManager->remove($friendship);
        $this->entityManager->flush();
    }

    public function findBetweenUsers(User $first, User $second): array
    {
        $q = $this->entityManager->createQuery('
              SELECT f FROM Angelov\Donut\Friendships\Friendship f WHERE 
                (f.user = :first AND f.friend = :second)
              OR
                (f.user = :second AND f.friend = :first)
            ')
            ->setParameter('first', $first)
            ->setParameter('second', $second);

        return $q->getResult();
    }
}
