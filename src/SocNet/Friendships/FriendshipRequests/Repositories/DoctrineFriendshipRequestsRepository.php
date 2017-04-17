<?php

namespace SocNet\Friendships\FriendshipRequests\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class DoctrineFriendshipRequestsRepository implements FriendshipRequestsRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(FriendshipRequest $request): void
    {
        $this->em->persist($request);
        $this->em->flush();
    }
}
