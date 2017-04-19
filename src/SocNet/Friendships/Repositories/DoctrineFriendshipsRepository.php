<?php

namespace SocNet\Friendships\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\Friendship;

class DoctrineFriendshipsRepository implements FriendshipsRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function store(Friendship $friendship): void
    {
        $this->entityManager->persist($friendship);
        $this->entityManager->flush();
    }
}
