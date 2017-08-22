<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker;

use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;

class PageExistenceChecker implements PageExistenceCheckerInterface
{
    public function hasNextPage(PaginatableResultsInterface $list, int $currentPage = 1): bool
    {
        return ($list->getOffset() + $list->getItemsPerPage()) <= ($list->countTotal() - 1);
    }

    public function hasPreviousPage(PaginatableResultsInterface $list, int $currentPage = 1): bool
    {
        return $currentPage > 1;
    }
}
