<?php

namespace Angelov\Donut\Thoughts\ThoughtsFeed;

use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;
use Angelov\Donut\Core\ResultLists\Sorting\SortableResultsInterface;
use Angelov\Donut\Thoughts\Thought;

interface ThoughtsFeedInterface extends PaginatableResultsInterface, SortableResultsInterface
{
    const FROM_ALL_USERS = 1;
    const FROM_FRIENDS = 2;

    public function filterSource(int $source) : void;

    public function includeOwnThoughts(bool $includeOwn = true) : void;

    /**
     * @return Thought[]
     */
    public function getResults() : array;
}
