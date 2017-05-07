<?php

namespace SocNet\Tests\Users\Repositories;

use AppBundle\Factories\UsersFactory;
use SocNet\Core\Exceptions\ResourceNotFoundException;
use SocNet\Users\Repositories\DoctrineUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Users\User;
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
