<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver;

interface RouteNameResolverInterface
{
    public function resolveCurrentRouteName() : string;
}
