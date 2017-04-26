<?php

namespace SocNet\Core\ResultLists\Pagination\CurrentPageResolvers;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestCurrentPageResolver implements CurrentPageResolverInterface
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function resolve(string $pageAttribute = 'page'): int
    {
        $queryValue = $this->request->get($pageAttribute);

        if (is_int($queryValue)) {
            return $queryValue;
        }

        return 1;
    }
}
