<?php

namespace AppBundle\Factories;

use Faker\Generator;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Places\City;

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

    private function generateInitialObject()
    {
        $id = $this->uuidGenerator->generate();
        $name = $this->faker->city;

        $this->generated = new City($id, $name);
    }

    public function get()
    {
        return $this->generated;
    }
}
