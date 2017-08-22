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

namespace Angelov\Donut\Tests\Communities\Repositories;

use AppBundle\Factories\CommunitiesFactory;
use AppBundle\Factories\UsersFactory;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Repositories\DoctrineCommunitiesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineCommunitiesRepositoryTest extends KernelTestCase
{
    /**
     * @var DoctrineCommunitiesRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UsersFactory
     */
    private $usersFactory;

    /**
     * @var CommunitiesFactory
     */
    private $communitiesFactory;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.communities.repositories.doctrine');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
        $this->communitiesFactory = $kernel->getContainer()->get('app.factories.communities.faker');
    }

    /** @test */
    public function it_stores_communities()
    {
        $community = $this->communitiesFactory->get();

        $this->repository->store($community);

        $id = $community->getId();

        $found = $this->em->find(Community::class, $id);

        $this->assertInstanceOf(Community::class, $found);
    }

    /** @test */
    public function it_updates_existing_communities()
    {
        $community = $this->communitiesFactory->get();

        $this->repository->store($community);

        $id = $community->getId();

        $community->setName('Updated name');

        $found = $this->repository->find($id);

        $this->assertSame('Updated name', $found->getName());
    }

    /** @test */
    public function it_finds_communities_by_id()
    {
        $author = $this->usersFactory->get();

        $community = $this->communitiesFactory
            ->withName('Example community')
            ->withDescription('This is just an example')
            ->createdBy($author)
            ->get();

        $this->em->persist($community);
        $this->em->flush();

        $id = $community->getId();

        $found = $this->repository->find($id);

        $this->assertInstanceOf(Community::class, $found);

        $this->assertSame('Example community', $found->getName());
        $this->assertSame('This is just an example', $found->getDescription());
        $this->assertSame($author, $found->getAuthor());
    }

    /** @test */
    public function it_throws_exception_for_non_existing_ids()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->repository->find('123');
    }

    /** @test */
    public function it_returns_array_of_all_communities()
    {
        $community = $this->communitiesFactory->get();
        $secondCommunity = $this->communitiesFactory->get();

        $this->em->persist($community);
        $this->em->persist($secondCommunity);

        $this->em->flush();

        $all = $this->repository->all();

        $this->assertTrue(is_array($all));
        $this->assertCount(2, $all);
        $this->assertContains($community, $all);
        $this->assertContains($secondCommunity, $all);
    }
}
