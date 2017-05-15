<?php

namespace AppBundle\Factories;

use Faker\Generator as Faker;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;

class ThoughtsFactory
{
    private $faker;
    private $uuidGenerator;
    private $usersFactory;

    private $content;
    private $author;

    public function __construct(Faker $faker, UuidGeneratorInterface $uuidGenerator, UsersFactory $usersFactory)
    {
        $this->faker = $faker;
        $this->uuidGenerator = $uuidGenerator;
        $this->usersFactory = $usersFactory;
    }

    public function sharedBy(User $user) : ThoughtsFactory
    {
        $this->author = $user;
        return $this;
    }

    public function withContent(string $content) : ThoughtsFactory
    {
        $this->content = $content;
        return $this;
    }

    public function get() : Thought
    {
        return new Thought(
            $this->uuidGenerator->generate(),
            $this->author ?? $this->usersFactory->get(),
            $this->content ?? $this->faker->sentence
        );
    }
}
