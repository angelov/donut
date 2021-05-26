<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2019, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2019, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Donut\Communities\Repositories;

use Angelov\Donut\Tests\Donut\TestCase;
use AppBundle\Factories\CommunitiesFactory;
use AppBundle\Factories\UsersFactory;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Repositories\DoctrineCommunitiesRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCommunitiesRepositoryTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getService(DoctrineCommunitiesRepository::class);
        $this->em = $this->getService(EntityManagerInterface::class);
        $this->usersFactory = $this->getService(UsersFactory::class);
        $this->communitiesFactory = $this->getService(CommunitiesFactory::class);
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
