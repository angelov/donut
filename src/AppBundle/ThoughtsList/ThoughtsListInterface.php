<?php

namespace AppBundle\ThoughtsList;

use SocNet\Thoughts\Thought;

interface ThoughtsListInterface
{
    const FROM_ALL_USERS = 1;
    const FROM_FRIENDS = 2;

    public function filterSource(int $source) : ThoughtsListInterface;

    public function includeOwnThoughts(bool $includeOwn = true) : ThoughtsListInterface;

    public function setOffset(int $offset) : ThoughtsListInterface;

    public function setItemsPerPage(int $items) : ThoughtsListInterface;

    public function orderBy(string $field, string $direction = 'ASC') : ThoughtsListInterface;

    public function countTotal() : int;

    /**
     * @return Thought[]
     */
    public function getResults() : array;
}
