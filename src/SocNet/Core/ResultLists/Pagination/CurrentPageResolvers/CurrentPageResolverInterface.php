<?php

namespace SocNet\Core\ResultLists\Pagination\CurrentPageResolvers;

interface CurrentPageResolverInterface
{
    public function resolve(string $pageAttribute = 'page') : int;
}
