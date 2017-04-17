<?php

namespace spec\SocNet\Friendships\FriendshipRequests\Repositories;

use PhpSpec\ObjectBehavior;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\FriendshipRequests\Repositories\DoctrineFriendshipRequestsRepository;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class DoctrineFriendshipRequestsRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineFriendshipRequestsRepository::class);
    }

    function it_is_friendship_requests_repository()
    {
        $this->shouldImplement(FriendshipRequestsRepositoryInterface::class);
    }
}
