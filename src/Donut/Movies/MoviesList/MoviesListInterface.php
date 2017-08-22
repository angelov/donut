<?php

namespace Angelov\Donut\Movies\MoviesList;

use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;
use Angelov\Donut\Core\ResultLists\Sorting\SortableResultsInterface;
use Angelov\Donut\Movies\Genre;
use Angelov\Donut\Movies\Movie;

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
