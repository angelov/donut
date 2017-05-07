<?php

namespace AppBundle\Factories;

use Faker\Generator;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Friendship;
use SocNet\Places\City;
use SocNet\Users\User;

class FriendshipsFactory
{
    private $usersFactory;
    private $uuidGenerator;

    private $user;
    private $friend;

    public function __construct(UuidGeneratorInterface $uuidGenerator, UsersFactory $usersFactory)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->usersFactory = $usersFactory;
    }

    public function from(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function to(User $user)
    {
        $this->friend = $user;
        return $this;
    }

    public function get()
    {
        return new Friendship(
            $this->uuidGenerator->generate(),
            $this->user ?? $this->usersFactory->get(),
            $this->friend ?? $this->usersFactory->get()
        );
    }
}
