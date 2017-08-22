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

namespace spec\Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator;

use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver\RouteNameResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\PaginationUrlGeneratorInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RoutingPaginationUrlGenerator;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\UrlPropertiesResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class RoutingPaginationUrlGeneratorSpec extends ObjectBehavior
{
    public function let(
        RouterInterface $router,
        CurrentPageResolverInterface $currentPageResolver,
        RouteNameResolverInterface $routeNameResolver,
        UrlPropertiesResolverInterface $urlPropertiesResolver
    ) {
        $this->beConstructedWith($router, $currentPageResolver, $routeNameResolver, $urlPropertiesResolver);

        $currentPageResolver->resolve('page')->willReturn(2);
        $routeNameResolver->resolveCurrentRouteName()->willReturn('example');
        $urlPropertiesResolver->resolve()->willReturn([
            'page' => 2,
            'filters' => 'disabled'
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RoutingPaginationUrlGenerator::class);
    }

    function it_is_pagination_url_generator()
    {
        $this->shouldImplement(PaginationUrlGeneratorInterface::class);
    }

    function it_generates_url_to_next_page(RouterInterface $router)
    {
        $router->generate('example', ['page' => 3, 'filters' => 'disabled'])
            ->shouldBeCalled()
            ->willReturn('http://localhost/example?page=3');

        $this->generateNextPageUrl()->shouldReturn('http://localhost/example?page=3');
    }

    function it_generates_url_to_previous_page(RouterInterface $router)
    {
        $router->generate('example', ['page' => 1, 'filters' => 'disabled'])
            ->shouldBeCalled()
            ->willReturn('http://localhost/example?page=1');

        $this->generatePreviousPageUrl()->shouldReturn('http://localhost/example?page=1');
    }
}
