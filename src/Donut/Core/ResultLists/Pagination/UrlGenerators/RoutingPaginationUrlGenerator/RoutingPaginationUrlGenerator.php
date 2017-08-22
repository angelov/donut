<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

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
