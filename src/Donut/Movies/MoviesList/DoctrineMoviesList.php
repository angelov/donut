<?php

namespace Angelov\Donut\Movies\MoviesList;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Angelov\Donut\Core\ResultLists\AbstractDoctrineResultsList;
use Angelov\Donut\Movies\Genre;
use Angelov\Donut\Movies\Movie;

class DoctrineMoviesList extends AbstractDoctrineResultsList implements MoviesListInterface
{
    /** @var Genre[] */
    private $genres = [];
    private $yearFrom;
    private $yearTo;

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Genre[] $genres
     */
    public function filterByGenres(array $genres) : void
    {
        $this->genres = $genres;
    }

    public function filterByPeriod(int $begin, int $end) : void
    {
        $this->yearFrom = $begin;
        $this->yearTo = $end;
    }

    protected function prepareQuery() : Query
    {
        $qb = $this->em->createQueryBuilder()
            ->select('movie')
            ->from(Movie::class, 'movie');

        foreach ($this->getOrderFields() as $order) {
            $qb->addOrderBy($order->getField(), $order->getDirection());
        }

        if (count($this->genres)) {
            $i = 0;
            foreach ($this->genres as $genre) {
                $param = 'genre_' . $i++;
                $qb->andWhere(sprintf(':%s member of movie.genres', $param))->setParameter($param, $genre);
            }
        }

        if ($this->yearFrom !== null) {
            $qb->andWhere('movie.year >= :yearFrom')->setParameter('yearFrom', $this->yearFrom);
        }

        if ($this->yearTo !== null) {
            $qb->andWhere('movie.year <= :yearTo')->setParameter('yearTo', $this->yearTo);
        }

        return $qb->getQuery();
    }
}
