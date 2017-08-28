<?php

namespace Angelov\Donut\Places\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Places\City;
use Doctrine\ORM\EntityManagerInterface;
use Donut\Places\Repositories\CitiesRepositoryInterface;

class DoctrineCitiesRepository implements CitiesRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $id) : City
    {
        $found = $this->entityManager->find(City::class, $id);

        if ($found) {
            return $found;
        }

        throw new ResourceNotFoundException();
    }
}
