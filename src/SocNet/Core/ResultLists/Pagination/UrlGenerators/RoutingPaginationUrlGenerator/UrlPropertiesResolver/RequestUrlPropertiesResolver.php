<?php

namespace SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver;

use SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\UrlPropertiesResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestUrlPropertiesResolver implements UrlPropertiesResolverInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolve(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->query->all();
    }
}
