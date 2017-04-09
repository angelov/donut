<?php

namespace SocNet\Tests\Users\Repositories;

use SocNet\Users\Repositories\UsersRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineUsersRepositoryTest extends KernelTestCase
{
    /** @var UsersRepositoryInterface */
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
        $user = new User('John', 'john@example.com', '123456');

        $this->repository->store($user);

        $id = $user->getId();

        $found = $this->entityManager->find(User::class, $id);

        $this->assertInstanceOf(User::class, $found);
    }
}
