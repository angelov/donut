<?php

namespace AppBundle\Factories;

use Faker\Generator;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Places\City;

class CitiesFactory
{
    private $faker;
    private $uuidGenerator;

    private $generated;

    public function __construct(Generator $faker, UuidGeneratorInterface $uuidGenerator)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;

        $this->generateInitialObject();
    }

    private function generateInitialObject() : void
    {
        $id = $this->uuidGenerator->generate();
        $name = $this->faker->city;

        $this->generated = new City($id, $name);
    }

    public function get() : City
    {
        return $this->generated;
    }
}
