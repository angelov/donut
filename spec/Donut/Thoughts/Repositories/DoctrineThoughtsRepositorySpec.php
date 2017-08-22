<?php

namespace spec\Angelov\Donut\Thoughts\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Thoughts\Repositories\DoctrineThoughtsRepository;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;

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
