<?php

namespace AppBundle\MutualFriendsResolver\UsersProvider;

use AppBundle\Entity\User;
use AppBundle\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderUsersProvider implements UsersProviderInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): User
    {
        $qb = $this->entityManager->createQueryBuilder();

        $results = $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->setMaxResults(1)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if (! count($results)) {
            throw new ResourceNotFoundException(sprintf(
                'Could not find an User with the given id [%d]',
                $id
            ));
        }

        return $results[0];
    }
}
