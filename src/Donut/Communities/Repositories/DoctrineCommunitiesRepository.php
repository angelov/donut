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

namespace Angelov\Donut\Communities\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Communities\Community;

class DoctrineCommunitiesRepository implements CommunitiesRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(Community $community): void
    {
        $this->em->persist($community);
        $this->em->flush();
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function find(string $id): Community
    {
        $found = $this->getBaseRepository()->find($id);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    private function getBaseRepository() : ObjectRepository
    {
        return $this->em->getRepository(Community::class);
    }

    public function all() : array
    {
        return $this->getBaseRepository()->findAll();
    }
}
