<?php

namespace SocNet\Core\ResultLists\Pagination;

interface PaginatableResultsInterface
{
    public function setOffset(int $offset) : void;

    public function getOffset() : int;

    public function setItemsPerPage(int $items) : void;

    public function getItemsPerPage() : int;

    public function countTotal() : int;

    public function getResults() : array;
}
