<?php

namespace AppBundle\MoviesList;

use SocNet\Movies\Genre;
use SocNet\Movies\Movie;

interface MoviesListInterface
{
    /**
     * @param $genres Genre[]
     */
    public function filterByGenres(array $genres) : MoviesListInterface;

    public function filterByPeriod(int $begin, int $end) : MoviesListInterface;

    public function setOffset(int $offset) : MoviesListInterface;

    public function setItemsPerPage(int $items) : MoviesListInterface;

    public function orderBy(string $field, string $direction = 'ASC') : MoviesListInterface;

    public function countTotal() : int;

    /**
     * @return Movie[]
     */
    public function getResults() : array;
}
