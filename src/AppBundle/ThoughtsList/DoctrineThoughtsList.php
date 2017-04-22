<?php

namespace AppBundle\ThoughtsList;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;

class DoctrineThoughtsList implements ThoughtsListInterface
{
    private $queryBuilder;
    private $currentUser;

    private $offset;
    private $maxResults;
    private $includeOwnThoughts = true;

    public function __construct(EntityManagerInterface $entityManager, User $currentUser)
    {
        $this->queryBuilder = $entityManager
            ->createQueryBuilder()
            ->select('thought')
            ->from(Thought::class, 'thought')
            ->join('thought.author', 'author');
        $this->currentUser = $currentUser;
    }

    public function orderBy(string $field, string $direction = 'ASC') : ThoughtsListInterface
    {
        $this->queryBuilder->orderBy($field, $direction);

        return $this;
    }

    public function filterSource(int $source) : ThoughtsListInterface
    {
        switch ($source) {
            case ThoughtsListInterface::FROM_FRIENDS:
                $this->queryBuilder
                    ->join('author.friendships', 'friendship')
                    ->where('friendship.friend = :current')
                    ->setParameter('current', $this->currentUser);
                break;
        }

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
        if ($this->includeOwnThoughts) {
            $this->addIncludeOwnThoughtsConstraint();
        }

        // http://stackoverflow.com/a/15077771
        $query = $this->queryBuilder->getQuery();

        $paginator = new Paginator($query);

        $query->setFirstResult($this->offset);
        $query->setMaxResults($this->maxResults);

        return $paginator->getIterator()->getArrayCopy();
    }

    private function addIncludeOwnThoughtsConstraint() : void
    {
        $this->queryBuilder
            ->orWhere('author = :current')
            ->setParameter('current', $this->currentUser);
    }

    public function countTotal() : int
    {
        $paginator = new Paginator($this->queryBuilder->getQuery());

        return count($paginator);
    }
}
