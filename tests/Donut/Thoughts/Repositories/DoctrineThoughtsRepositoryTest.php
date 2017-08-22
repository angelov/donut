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

namespace Angelov\Donut\Tests\Thoughts\Repositories;

use AppBundle\Factories\ThoughtsFactory;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Thoughts\Repositories\DoctrineThoughtsRepository;
use Angelov\Donut\Thoughts\Thought;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineThoughtsRepositoryTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var DoctrineThoughtsRepository */
    private $repository;

    /** @var ThoughtsFactory */
    private $thoughtsFactory;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.thoughts.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->thoughtsFactory = $kernel->getContainer()->get('app.factories.thoughts.faker');
    }

    /** @test */
    public function it_stores_new_thoughts()
    {
        $thought = $this->thoughtsFactory->get();

        $this->repository->store($thought);

        $id = $thought->getId();

        $found = $this->em->find(Thought::class, $id);

        $this->assertInstanceOf(Thought::class, $found);
    }

    /** @test */
    public function it_removes_thoughts_from_database()
    {
        $thought = $this->thoughtsFactory->get();

        $this->repository->store($thought);

        $id = $thought->getId();

        $this->repository->destroy($thought);

        $found = $this->em->find(Thought::class, $id);

        $this->assertNull($found);
    }
}
