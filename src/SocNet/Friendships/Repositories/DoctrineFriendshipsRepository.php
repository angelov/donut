<?php

namespace SocNet\Friendships\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Friendships\Friendship;
use SocNet\Users\User;

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

    public function destroy(Friendship $friendship): void
    {
        $this->entityManager->remove($friendship);
        $this->entityManager->flush();
    }

    public function findBetweenUsers(User $first, User $second): array
    {
        $q = $this->entityManager->createQuery('
              SELECT f FROM Socnet\Friendships\Friendship f WHERE 
                (f.user = :first AND f.friend = :second)
              OR
                (f.user = :second AND f.friend = :first)
            ')
            ->setParameter('first', $first)
            ->setParameter('second', $second);

        return $q->getResult();
    }
}
