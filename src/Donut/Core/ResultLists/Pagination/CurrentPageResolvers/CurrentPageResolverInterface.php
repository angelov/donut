<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers;

interface CurrentPageResolverInterface
{
    public function resolve(string $pageAttribute = 'page') : int;
}
