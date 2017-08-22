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

namespace spec\Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver;

use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\RequestUrlPropertiesResolver;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\UrlPropertiesResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestUrlPropertiesResolverSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestUrlPropertiesResolver::class);
    }

    function it_is_url_properties_resolver()
    {
        $this->shouldImplement(UrlPropertiesResolverInterface::class);
    }

    function it_reads_the_query_attributes_from_request(RequestStack $requestStack)
    {
        $request = new Request();
        $request->query->set('first', 'value1');
        $request->query->set('second', 'value2');

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->resolve()->shouldReturn([
            'first' => 'value1',
            'second' => 'value2'
        ]);
    }

    function it_returns_empty_array_when_there_are_no_query_attributes(RequestStack $requestStack)
    {
        $request = new Request();

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->resolve()->shouldReturn([]);
    }
}
