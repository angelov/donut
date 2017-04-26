<?php

namespace spec\SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver;

use PhpSpec\ObjectBehavior;
use SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver\RequestRouteNameResolver;
use SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\RouteNameResolver\RouteNameResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestRouteNameResolverSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack)
    {
        $request = new Request();
        $request->attributes->set('_route', 'example');

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestRouteNameResolver::class);
    }

    function it_is_route_name_resolver()
    {
        $this->shouldImplement(RouteNameResolverInterface::class);
    }

    function it_reads_the_route_name_from_the_request()
    {
        $this->resolveCurrentRouteName()->shouldReturn('example');
    }
}
