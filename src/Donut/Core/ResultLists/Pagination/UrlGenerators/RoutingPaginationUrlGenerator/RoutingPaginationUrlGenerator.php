<?php

namespace Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator;

use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver\RouteNameResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\PaginationUrlGeneratorInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\UrlPropertiesResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class RoutingPaginationUrlGenerator implements PaginationUrlGeneratorInterface
{
    private $router;
    private $currentPageResolver;
    private $routeNameResolver;
    private $urlPropertiesResolver;

    public function __construct(
        RouterInterface $router,
        CurrentPageResolverInterface $currentPageResolver,
        RouteNameResolverInterface $routeNameResolver,
        UrlPropertiesResolverInterface $urlPropertiesResolver
    ) {
        $this->router = $router;
        $this->currentPageResolver = $currentPageResolver;
        $this->routeNameResolver = $routeNameResolver;
        $this->urlPropertiesResolver = $urlPropertiesResolver;
    }

    public function generateNextPageUrl(string $pageAttribute = 'page'): string
    {
        $currentPage = $this->currentPageResolver->resolve($pageAttribute);

        return $this->generateUrlToPage($currentPage + 1);
    }

    public function generatePreviousPageUrl(string $pageAttribute = 'page'): string
    {
        $currentPage = $this->currentPageResolver->resolve($pageAttribute);

        return $this->generateUrlToPage($currentPage - 1);
    }

    private function generateUrlToPage(int $page) : string
    {
        $routeName = $this->routeNameResolver->resolveCurrentRouteName();
        $urlParameters = $this->urlPropertiesResolver->resolve();

        $urlParameters['page'] = $page;

        return $this->router->generate($routeName, $urlParameters);
    }
}
