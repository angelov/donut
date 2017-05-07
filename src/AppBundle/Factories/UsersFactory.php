<?php

namespace AppBundle\Factories;

use Faker\Generator as Faker;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Places\City;
use SocNet\Users\User;

class UsersFactory
{
    private $faker;
    private $uuidGenerator;
    private $citiesFactory;

    private $name;
    private $email;
    private $password;
    private $city;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, CitiesFactory $citiesFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->citiesFactory = $citiesFactory;
    }

    public function withName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function withEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function withPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function fromCity(City $city)
    {
        $this->city = $city;
    }

    public function get() : User
    {
        return new User(
            $this->uuidGenerator->generate(),
            $this->name ?? $this->faker->name,
            $this->email ?? $this->faker->safeEmail,
            $this->password ?? $this->faker->password,
            $this->city ?? $this->citiesFactory->get()
        );
    }
}
