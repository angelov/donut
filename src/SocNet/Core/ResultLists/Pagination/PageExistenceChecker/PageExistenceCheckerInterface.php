<?php

namespace SocNet\Core\ResultLists\Pagination\PageExistenceChecker;

use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;

interface PageExistenceCheckerInterface
{
    public function hasNextPage(PaginatableResultsInterface $list, int $currentPage = 1) : bool;

    public function hasPreviousPage(PaginatableResultsInterface $list, int $currentPage = 1) : bool;
}
