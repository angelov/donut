<?php

namespace spec\Angelov\Donut\Places\Repositories;

use Angelov\Donut\Places\Repositories\DoctrineCitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Places\Repositories\CitiesRepositoryInterface;
use PhpSpec\ObjectBehavior;

class DoctrineCitiesRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineCitiesRepository::class);
    }

    function it_is_cities_repository()
    {
        $this->shouldImplement(CitiesRepositoryInterface::class);
    }
}
