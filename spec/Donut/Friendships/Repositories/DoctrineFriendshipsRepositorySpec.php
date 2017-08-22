<?php

namespace spec\Angelov\Donut\Friendships\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Friendships\Repositories\DoctrineFriendshipsRepository;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;

class DoctrineFriendshipsRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineFriendshipsRepository::class);
    }

    function it_is_friendships_repository()
    {
        $this->shouldImplement(FriendshipsRepositoryInterface::class);
    }
}
