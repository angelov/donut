<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Places\Repositories;

use Angelov\Donut\Places\City;
use Angelov\Donut\Places\Repositories\DoctrineCitiesRepository;
use Angelov\Donut\Tests\Donut\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCitiesRepositoryTest extends TestCase
{
    /** @var DoctrineCitiesRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->getService(DoctrineCitiesRepository::class);
        $this->entityManager = $this->getService(EntityManagerInterface::class);
    }

    /** @test */
    public function it_finds_cities_by_id()
    {
        $toBeFound = new City('1', 'To be found');
        $nonImportant = new City('2', 'Non-important');

        $em = $this->entityManager;

        $em->persist($toBeFound);
        $em->persist($nonImportant);

        $em->flush();

        $found = $this->repository->find('1');

        $this->assertEquals('To be found', $found->getName());
    }
}
