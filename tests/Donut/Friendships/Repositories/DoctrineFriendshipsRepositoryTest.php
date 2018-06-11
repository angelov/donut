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

namespace Angelov\Donut\Tests\Friendships\FriendshipRequests\Repositories;

use Angelov\Donut\Tests\Donut\TestCase;
use AppBundle\Factories\FriendshipsFactory;
use AppBundle\Factories\UsersFactory;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Repositories\DoctrineFriendshipsRepository;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class DoctrineFriendshipsRepositoryTest extends TestCase
{
    /** @var DoctrineFriendshipsRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FriendshipsFactory */
    private $friendshipsFactory;

    /** @var UsersFactory */
    private $usersFactory;

    /** @var UsersRepositoryInterface */
    private $usersRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->getService(DoctrineFriendshipsRepository::class);
        $this->entityManager = $this->getService(EntityManagerInterface::class);
        $this->friendshipsFactory = $this->getService(FriendshipsFactory::class);
        $this->usersFactory = $this->getService(UsersFactory::class);
        $this->usersRepository = $this->getService(UsersRepositoryInterface::class);
    }

    /** @test */
    public function it_finds_friendships_by_id()
    {
        $nonImportant = $this->friendshipsFactory->get();
        $toBeFound = $this->friendshipsFactory->get();

        $this->repository->store($nonImportant);
        $this->repository->store($toBeFound);

        $found = $this->repository->find($toBeFound->getId());

        $this->assertSame($toBeFound->getId(), $found->getId());
    }

    /** @test */
    public function it_stores_friendships()
    {
        $friendship = $this->friendshipsFactory->get();

        $this->repository->store($friendship);

        $id = $friendship->getId();

        $found = $this->entityManager->find(Friendship::class, $id);

        $this->assertInstanceOf(Friendship::class, $found);
    }

    /** @test */
    public function it_deletes_friendships()
    {
        $friendship = $this->friendshipsFactory->get();

        $this->repository->store($friendship);

        $id = $friendship->getId();

        $this->repository->destroy($friendship);

        $found = $this->entityManager->find(Friendship::class, $id);

        $this->assertNull($found);
    }

    /** @test */
    public function it_finds_friendships_between_users()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $friendship = $this->friendshipsFactory->from($sender)->to($recipient)->get();
        $this->repository->store($friendship);

        $friendship2 = $this->friendshipsFactory->from($recipient)->to($sender)->get();
        $this->repository->store($friendship2);

        $res = $this->repository->findBetweenUsers($sender, $recipient);

        $this->assertCount(2, $res);
        $this->assertTrue(in_array($friendship, $res, true));
        $this->assertTrue(in_array($friendship2, $res, true));
    }

    /** @test */
    public function it_returns_empty_array_when_no_friendships_between_users()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $this->usersRepository->store($sender);
        $this->usersRepository->store($recipient);

        $res = $this->repository->findBetweenUsers($sender, $recipient);

        $this->assertTrue(is_array($res));
        $this->assertCount(0, $res);
    }
}
