<?php

namespace Angelov\Donut\Tests\Places\Repositories;

use Angelov\Donut\Places\City;
use Angelov\Donut\Places\Repositories\DoctrineCitiesRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Kernel;

class DoctrineCitiesRepositoryTest extends KernelTestCase
{
    /** @var DoctrineCitiesRepository */
    private $repository;

    /** @var  Kernel */
    private $bootedkernel;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->bootedkernel = $kernel;

        $this->repository = $kernel->getContainer()->get('app.places.cities.repository.doctrine');
    }

    /** @test */
    public function it_finds_cities_by_id()
    {
        $toBeFound = new City('1', 'To be found');
        $nonImportant = new City('2', 'Non-important');

        $em = $this->bootedkernel->getContainer()->get('doctrine.orm.entity_manager');

        $em->persist($toBeFound);
        $em->persist($nonImportant);

        $em->flush();

        $found = $this->repository->find('1');

        $this->assertEquals('To be found', $found->getName());
    }
}
