<?php

namespace AppBundle\Factories;

use Faker\Generator as Faker;
use SocNet\Communities\Community;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Users\User;

class CommunitiesFactory
{
    private $faker;
    private $uuidGenerator;
    private $usersFactory;

    private $name;
    private $description;
    private $author;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, UsersFactory $usersFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->usersFactory = $usersFactory;
    }

    public function withName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function withDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function createdBy(User $author)
    {
        $this->author = $author;
        return $this;
    }

    public function get()
    {
        return new Community(
            $this->uuidGenerator->generate(),
            $this->name ?? $this->faker->name,
            $this->author ?? $this->usersFactory->get(),
            $this->description ?? $this->faker->sentence
        );
    }
}
