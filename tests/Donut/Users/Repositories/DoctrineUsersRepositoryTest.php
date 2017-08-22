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

namespace Angelov\Donut\Tests\Users\Repositories;

use AppBundle\Factories\UsersFactory;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\DoctrineUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineUsersRepositoryTest extends KernelTestCase
{
    /** @var DoctrineUsersRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UsersFactory */
    private $usersFactory;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.users.repository.doctrine');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
    }

    /** @test */
    public function it_stores_users()
    {
        $user = $this->usersFactory->get();

        $this->repository->store($user);

        $id = $user->getId();

        $found = $this->entityManager->find(User::class, $id);

        $this->assertInstanceOf(User::class, $found);
    }

    /** @test */
    public function it_finds_users_by_email()
    {
        $nonImportant = $this->usersFactory->withEmail('something@example.net')->get();
        $toBeFound = $this->usersFactory->withEmail('james@example.net')->get();

        $this->repository->store($nonImportant);
        $this->repository->store($toBeFound);

        $found = $this->repository->findByEmail('james@example.net');

        $this->assertTrue($found->equals($toBeFound));
    }

    /** @test */
    public function it_throws_exception_for_non_existing_emails()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->repository->findByEmail('james@example.net');
    }

    /** @test */
    public function it_finds_users_by_id()
    {
        $nonImportant = $this->usersFactory->get();
        $toBeFound = $this->usersFactory->get();

        $this->repository->store($nonImportant);
        $this->repository->store($toBeFound);

        $found = $this->repository->find($toBeFound->getId());

        $this->assertTrue($found->equals($toBeFound));
    }

    /** @test */
    public function it_throws_exception_for_non_existing_ids()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->repository->find('123');
    }

    /** @test */
    public function it_returns_array_of_all_users()
    {
        $first = $this->usersFactory->get();
        $second = $this->usersFactory->get();

        $this->repository->store($first);
        $this->repository->store($second);

        $all = $this->repository->all();

        $this->assertCount(2, $all);
        $this->assertContains($first, $all);
        $this->assertContains($second, $all);
    }

    /** @test */
    public function it_returns_empty_array_when_there_are_no_users()
    {
        $all = $this->repository->all();

        $this->assertCount(0, $all);
    }
}
