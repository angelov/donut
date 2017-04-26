<?php

namespace spec\SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver;

use PhpSpec\ObjectBehavior;
use SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\RequestUrlPropertiesResolver;
use SocNet\Core\ResultLists\Pagination\UrlGenerators\RoutingPaginationUrlGenerator\UrlPropertiesResolver\UrlPropertiesResolverInterface;
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
