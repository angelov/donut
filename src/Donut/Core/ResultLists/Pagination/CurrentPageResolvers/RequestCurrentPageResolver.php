<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers;

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

        if (is_numeric($queryValue)) {
            return (int) $queryValue;
        }

        return 1;
    }
}
