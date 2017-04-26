<?php

namespace SocNet\Core\ResultLists\Pagination\Renderers;

use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;

interface PaginationRendererInterface
{
    public function render(PaginatableResultsInterface $list, string $pageAttribute = 'page') : string;
}
