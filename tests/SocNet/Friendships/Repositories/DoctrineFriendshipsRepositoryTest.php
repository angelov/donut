<?php

namespace SocNet\Tests\Friendships\FriendshipRequests\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\Repositories\DoctrineFriendshipsRepository;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineFriendshipsRepositoryTest extends KernelTestCase
{
    /** @var DoctrineFriendshipsRepository */
    private $repository;

    /** @var UsersRepositoryInterface */
    private $usersRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.friendships.repositories.doctrine');
        $this->usersRepository = $kernel->getContainer()->get('app.users.repository.default');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_stores_friendships()
    {
        $sender = new User('John', 'john@example.com', '123456');
        $this->usersRepository->store($sender);

        $recipient = new User('James', 'james@example.com', '123456');
        $this->usersRepository->store($recipient);

        $friendship = new Friendship($sender, $recipient);

        $this->repository->store($friendship);

        $id = $friendship->getId();

        $found = $this->entityManager->find(Friendship::class, $id);

        $this->assertInstanceOf(Friendship::class, $found);
    }
}
