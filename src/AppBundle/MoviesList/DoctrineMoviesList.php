<?php

namespace AppBundle\MoviesList;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SocNet\Movies\Genre;
use SocNet\Movies\Movie;

class DoctrineMoviesList implements MoviesListInterface
{
    /** @var Genre[] */
    private $genres = [];
    private $yearFrom;
    private $yearTo;
    /** @var OrderField[] */
    private $orderByFields = [];
    private $offset = 0;
    private $perPage = 10;

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function filterByGenres(array $genres): MoviesListInterface
    {
        $this->genres = $genres;

        return $this;
    }

    public function filterByPeriod(int $begin, int $end): MoviesListInterface
    {
        $this->yearFrom = $begin;
        $this->yearTo = $end;

        return $this;
    }

    public function setOffset(int $offset): MoviesListInterface
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setItemsPerPage(int $items): MoviesListInterface
    {
        $this->perPage = $items;

        return $this;
    }

    public function getItemsPerPage(): int
    {
        return $this->perPage;
    }

    public function orderBy(array $fields): MoviesListInterface
    {
        $this->orderByFields = $fields;

        return $this;
    }

    public function countTotal(): int
    {
        $paginator = new Paginator($this->prepareQuery());

        return count($paginator);
    }

    /**
     * @return Movie[]
     */
    public function getResults(): array
    {
        $query = $this->prepareQuery();

        $paginator = new Paginator($query);

        $query->setFirstResult($this->offset);
        $query->setMaxResults($this->perPage);

        return $paginator->getIterator()->getArrayCopy();
    }

    private function prepareQuery() : Query
    {
        $qb = $this->em->createQueryBuilder()
            ->select('movie')
            ->from(Movie::class, 'movie');

        foreach ($this->orderByFields as $order) {
            $qb->addOrderBy($order->getField(), $order->getDirection());
        }

        if (count($this->genres)) {
            foreach ($this->genres as $genre) {
                $param = 'genre_' . $genre->getId();
                $qb->andWhere(sprintf(':%s member of movie.genres', $param))->setParameter($param, $genre);
            }
        }

        if (isset($this->yearFrom)) {
            $qb->andWhere('movie.year >= :yearFrom')->setParameter('yearFrom', $this->yearFrom);
        }

        if (isset($this->yearTo)) {
            $qb->andWhere('movie.year <= :yearTo')->setParameter('yearTo', $this->yearTo);
        }

        return $qb->getQuery();
    }
}
