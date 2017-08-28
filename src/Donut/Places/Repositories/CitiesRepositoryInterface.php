<?php

namespace Donut\Places\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Places\City;

interface CitiesRepositoryInterface
{
    /**
     * @throws ResourceNotFoundException
     */
    public function find(string $id) : City;
}
