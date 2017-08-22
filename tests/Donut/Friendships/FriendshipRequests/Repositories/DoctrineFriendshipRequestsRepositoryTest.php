<?php

namespace Angelov\Donut\Tests\Friendships\FriendshipRequests\Repositories;

use AppBundle\Factories\UsersFactory;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\DoctrineFriendshipRequestsRepository;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineFriendshipRequestsRepositoryTest extends KernelTestCase
{
    /** @var DoctrineFriendshipRequestsRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UsersFactory */
    private $usersFactory;

    /** @var UuidGeneratorInterface */
    private $uuidGenerator;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->repository = $kernel->getContainer()->get('app.friendships.friendship_requests.repositories.doctrine');
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
        $this->uuidGenerator = $kernel->getContainer()->get('app.core.uuid_generator');
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
