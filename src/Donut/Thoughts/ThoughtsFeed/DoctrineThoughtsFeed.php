<?php

namespace Angelov\Donut\Thoughts\ThoughtsFeed;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Angelov\Donut\Core\ResultLists\AbstractDoctrineResultsList;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\ThoughtsFeed\ThoughtsFeedInterface;
use Angelov\Donut\Users\User;

class DoctrineThoughtsFeed extends AbstractDoctrineResultsList implements ThoughtsFeedInterface
{
    private $em;
    private $currentUser;

    private $includeOwnThoughts = true;
    private $filterSource;

    public function __construct(EntityManagerInterface $entityManager, User $currentUser)
    {
        $this->em = $entityManager;
        $this->currentUser = $currentUser;
    }

    public function filterSource(int $source) : void
    {
        $this->filterSource = $source;
    }

    public function includeOwnThoughts(bool $includeOwn = true) : void
    {
        $this->includeOwnThoughts = $includeOwn;
    }

    protected function prepareQuery() : Query
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('thought')
            ->from(Thought::class, 'thought')
            ->join('thought.author', 'author');

        switch ($this->filterSource) {
            case ThoughtsFeedInterface::FROM_FRIENDS:
                $queryBuilder
                    ->where('author in (:friends)')
                    ->setParameter('friends', $this->currentUser->getFriends());
                break;
        }

        if ($this->includeOwnThoughts && $this->filterSource === ThoughtsFeedInterface::FROM_FRIENDS) {
            $queryBuilder
                ->orWhere('author = :current')
                ->setParameter('current', $this->currentUser);
        }

        foreach ($this->getOrderFields() as $orderField) {
            $queryBuilder->addOrderBy($orderField->getField(), $orderField->getDirection());
        }

        return $queryBuilder->getQuery();
    }
}
