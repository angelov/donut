<?php

namespace SocNet\Movies\MoviesList;

use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;
use SocNet\Core\ResultLists\Sorting\SortableResultsInterface;
use SocNet\Movies\Genre;
use SocNet\Movies\Movie;

interface MoviesListInterface extends PaginatableResultsInterface, SortableResultsInterface
{
    /**
     * @param $genres Genre[]
     */
    public function filterByGenres(array $genres) : void;

    public function filterByPeriod(int $begin, int $end) : void;

    /**
     * @return Movie[]
     */
    public function getResults() : array;
}
