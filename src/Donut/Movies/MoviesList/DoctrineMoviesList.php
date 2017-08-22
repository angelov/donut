<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

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
