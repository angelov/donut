<?php

namespace spec\Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers;

use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\RequestCurrentPageResolver;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestCurrentPageResolverSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack)
    {
        $request = new Request();
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestCurrentPageResolver::class);
    }

    function it_is_current_page_resolver()
    {
        $this->shouldImplement(CurrentPageResolverInterface::class);
    }

    function it_returns_first_page_as_default_value()
    {
        $this->resolve()->shouldReturn(1);
    }

    function it_returns_first_page_when_page_attribute_is_not_a_number(RequestStack $requestStack)
    {
        $request = new Request();
        $request->query->set('page', 'second');

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->resolve()->shouldReturn(1);
    }

    function it_reads_the_page_from_request_query_attributes(RequestStack $requestStack)
    {
        $request = new Request();
        $request->query->set('page', '3');

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->resolve()->shouldReturn(3);
    }

    function it_resolves_the_page_with_different_page_query_attributes(RequestStack $requestStack)
    {
        $request = new Request();
        $request->query->set('pageNumber', '4');

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->resolve('pageNumber')->shouldReturn(4);
    }
}
