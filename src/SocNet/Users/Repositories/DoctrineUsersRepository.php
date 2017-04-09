<?php

namespace SocNet\Users\Repositories;

use AppBundle\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
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

    public function findByEmail(string $email): User
    {
        $found = $this->getRepository()->findOneBy(['email' => $email]);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    private function getRepository() : ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }
}
