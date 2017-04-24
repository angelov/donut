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

    public function getOffset() : int;

    public function setItemsPerPage(int $items) : MoviesListInterface;

    public function getItemsPerPage() : int;

    public function orderBy(array $fields) : MoviesListInterface;

    public function countTotal() : int;

    /**
     * @return Movie[]
     */
    public function getResults() : array;
}
