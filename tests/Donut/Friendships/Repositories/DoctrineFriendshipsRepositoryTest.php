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

namespace Angelov\Donut\Tests\Friendships\FriendshipRequests\Repositories;

use AppBundle\Factories\FriendshipsFactory;
use AppBundle\Factories\UsersFactory;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\Repositories\DoctrineFriendshipsRepository;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineFriendshipsRepositoryTest extends KernelTestCase
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

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.friendships.repositories.doctrine');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->friendshipsFactory = $kernel->getContainer()->get('app.factories.friendships');
        $this->usersFactory =  $kernel->getContainer()->get('app.factories.users.faker');
        $this->usersRepository = $kernel->getContainer()->get('app.users.repository.default');
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
