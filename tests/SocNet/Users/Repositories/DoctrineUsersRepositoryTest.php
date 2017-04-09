<?php

namespace SocNet\Tests\Users\Repositories;

use AppBundle\Exceptions\ResourceNotFoundException;
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

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.users.repository.doctrine');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_stores_users()
    {
        $user = new User('John', 'john@example.net', '123456');

        $this->repository->store($user);

        $id = $user->getId();

        $found = $this->entityManager->find(User::class, $id);

        $this->assertInstanceOf(User::class, $found);
    }

    /** @test */
    public function it_finds_users_by_email()
    {
        $this->repository->store(new User('John', 'john@example.net', '123456'));
        $this->repository->store($user = new User('James', 'james@example.net', '123456'));

        $found = $this->repository->findByEmail('james@example.net');

        $this->assertTrue($user->equals($found));
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
        $this->repository->store(new User('John', 'john@example.net', '123456'));
        $this->repository->store($user = new User('James', 'james@example.net', '123456'));

        $found = $this->repository->find($user->getId());

        $this->assertTrue($user->equals($found));
    }

    public function it_throws_exception_for_non_existing_ids()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->repository->find('123');
    }
}
