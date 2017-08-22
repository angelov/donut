<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\Renderers;

use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;

interface PaginationRendererInterface
{
    public function render(PaginatableResultsInterface $list, string $pageAttribute = 'page') : string;
}
