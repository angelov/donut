<?php

namespace SocNet\Tests\Friendships\FriendshipRequests\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Friendships\FriendshipRequests\Repositories\DoctrineFriendshipRequestsRepository;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineFriendshipRequestsRepositoryTest extends KernelTestCase
{
    /** @var DoctrineFriendshipRequestsRepository */
    private $repository;

    /** @var UsersRepositoryInterface */
    private $usersRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.friendships.friendship_requests.repositories.doctrine');
        $this->usersRepository = $kernel->getContainer()->get('app.users.repository.default');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    /** @test */
    public function it_stores_friendship_requests()
    {
        $sender = new User('John', 'john@example.com', '123456');
        $this->usersRepository->store($sender);

        $recipient = new User('James', 'james@example.com', '123456');
        $this->usersRepository->store($recipient);

        $friendshipRequest = new FriendshipRequest($sender, $recipient);

        $this->repository->store($friendshipRequest);

        $id = $friendshipRequest->getId();

        $found = $this->entityManager->find(FriendshipRequest::class, $id);

        $this->assertInstanceOf(FriendshipRequest::class, $found);
    }
}
