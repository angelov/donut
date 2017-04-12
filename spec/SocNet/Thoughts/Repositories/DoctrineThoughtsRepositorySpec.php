<?php

namespace spec\SocNet\Thoughts\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Thoughts\Repositories\DoctrineThoughtsRepository;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;

class DoctrineThoughtsRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineThoughtsRepository::class);
    }

    function it_is_thoughts_repository()
    {
        $this->shouldImplement(ThoughtsRepositoryInterface::class);
    }
}
