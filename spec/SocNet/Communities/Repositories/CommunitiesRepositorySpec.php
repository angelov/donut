<?php

namespace spec\SocNet\Communities\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Communities\Repositories\CommunitiesRepository;
use PhpSpec\ObjectBehavior;
use SocNet\Communities\Repositories\CommunityRepositoryInterface;

class CommunitiesRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommunitiesRepository::class);
    }

    function it_implements_community_repository_interface()
    {
        $this->shouldImplement(CommunityRepositoryInterface::class);
    }
}
