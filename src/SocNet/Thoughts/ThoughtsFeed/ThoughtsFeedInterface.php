<?php

namespace SocNet\Thoughts\ThoughtsFeed;

use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;
use SocNet\Core\ResultLists\Sorting\SortableResultsInterface;
use SocNet\Thoughts\Thought;

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
