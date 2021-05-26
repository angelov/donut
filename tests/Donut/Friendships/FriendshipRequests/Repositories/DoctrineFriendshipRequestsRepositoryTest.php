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

namespace Angelov\Donut\Tests\Donut\Friendships\FriendshipRequests\Repositories;

use Angelov\Donut\Tests\Donut\TestCase;
use AppBundle\Factories\UsersFactory;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\DoctrineFriendshipRequestsRepository;

class DoctrineFriendshipRequestsRepositoryTest extends TestCase
{
    /** @var DoctrineFriendshipRequestsRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UsersFactory */
    private $usersFactory;

    /** @var UuidGeneratorInterface */
    private $uuidGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getService(DoctrineFriendshipRequestsRepository::class);
        $this->entityManager = $this->getService(EntityManagerInterface::class);
        $this->usersFactory = $this->getService(UsersFactory::class);
        $this->uuidGenerator = $this->getService(UuidGeneratorInterface::class);
    }

    /** @test */
    public function it_stores_friendship_requests()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $friendshipRequest = new FriendshipRequest($this->uuidGenerator->generate(), $sender, $recipient);

        $this->repository->store($friendshipRequest);

        $id = $friendshipRequest->getId();

        $found = $this->entityManager->find(FriendshipRequest::class, $id);

        $this->assertInstanceOf(FriendshipRequest::class, $found);
    }

    /** @test */
    public function it_destroys_friendship_requests()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $friendshipRequest = new FriendshipRequest($this->uuidGenerator->generate(), $sender, $recipient);

        $this->repository->store($friendshipRequest);

        $id = $friendshipRequest->getId();

        $this->repository->destroy($friendshipRequest);

        $found = $this->entityManager->find(FriendshipRequest::class, $id);

        $this->assertNull($found);
    }
}
