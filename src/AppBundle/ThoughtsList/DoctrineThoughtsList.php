<?php

namespace AppBundle\ThoughtsList;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;

class DoctrineThoughtsList implements ThoughtsListInterface
{
    private $em;
    private $currentUser;

    private $offset;
    private $maxResults;
    private $includeOwnThoughts = true;
    private $orderBy;
    private $orderByDirection;
    private $filterSource;

    public function __construct(EntityManagerInterface $entityManager, User $currentUser)
    {
        $this->em = $entityManager;
        $this->currentUser = $currentUser;
    }

    public function orderBy(string $field, string $direction = 'ASC') : ThoughtsListInterface
    {
        $this->orderBy = $field;
        $this->orderByDirection = $direction;

        return $this;
    }

    public function filterSource(int $source) : ThoughtsListInterface
    {
        $this->filterSource = $source;

        return $this;
    }

    public function includeOwnThoughts(bool $includeOwn = true) : ThoughtsListInterface
    {
        $this->includeOwnThoughts = $includeOwn;

        return $this;
    }

    public function setOffset(int $offset) : ThoughtsListInterface
    {
        $this->offset = $offset;

        return $this;
    }

    public function setItemsPerPage(int $items) : ThoughtsListInterface
    {
        $this->maxResults = $items;

        return $this;
    }

    public function getResults() : array
    {
        // http://stackoverflow.com/a/15077771
        $query = $this->prepareQuery();

        $paginator = new Paginator($query);

        $query->setFirstResult($this->offset);
        $query->setMaxResults($this->maxResults);

        return $paginator->getIterator()->getArrayCopy();
    }

    private function prepareQuery() : Query
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('thought')
            ->from(Thought::class, 'thought')
            ->join('thought.author', 'author');

        switch ($this->filterSource) {
            case ThoughtsListInterface::FROM_FRIENDS:
                $queryBuilder
                    ->where('author in (:friends)')
                    ->setParameter('friends', $this->currentUser->getFriends());
                break;
        }

        if ($this->includeOwnThoughts && $this->filterSource === ThoughtsListInterface::FROM_FRIENDS) {
            $queryBuilder
                ->orWhere('author = :current')
                ->setParameter('current', $this->currentUser);
        }

        $queryBuilder->orderBy($this->orderBy, $this->orderByDirection);

        return $queryBuilder->getQuery();
    }

    public function countTotal() : int
    {
        $paginator = new Paginator($this->prepareQuery());

        return count($paginator);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getItemsPerPage(): int
    {
        return $this->maxResults;
    }
}
