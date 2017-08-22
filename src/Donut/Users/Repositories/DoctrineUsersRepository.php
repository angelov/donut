<?php

namespace Angelov\Donut\Users\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Users\User;

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

    /** @psalm-suppress MoreSpecificReturnType */
    public function findByEmail(string $email): User
    {
        $found = $this->getRepository()->findOneBy(['email' => $email]);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function find(string $id): User
    {
        $found = $this->getRepository()->find($id);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    private function getRepository() : ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    public function all(): array
    {
        return $this->getRepository()->findAll();
    }
}
