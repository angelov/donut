<?php

namespace SocNet\Users\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Users\User;

class DoctrineUsersRepository implements UsersRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function store(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
