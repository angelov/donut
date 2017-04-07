<?php

namespace spec\SocNet\Communities\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Communities\Repositories\DoctrineCommunitiesRepository;
use PhpSpec\ObjectBehavior;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;

class DoctrineCommunitiesRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineCommunitiesRepository::class);
    }

    function it_implements_community_repository_interface()
    {
        $this->shouldImplement(CommunitiesRepositoryInterface::class);
    }
}
