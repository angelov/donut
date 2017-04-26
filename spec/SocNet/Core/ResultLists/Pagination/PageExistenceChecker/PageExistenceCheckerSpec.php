<?php

namespace spec\SocNet\Core\ResultLists\Pagination\PageExistenceChecker;

use SocNet\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use SocNet\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceChecker;
use PhpSpec\ObjectBehavior;
use SocNet\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceCheckerInterface;
use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;

class PageExistenceCheckerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PageExistenceChecker::class);
    }

    function it_is_page_existence_checker()
    {
        $this->shouldImplement(PageExistenceCheckerInterface::class);
    }

    function it_returns_true_if_there_is_next_page(PaginatableResultsInterface $list)
    {
        $list->getOffset()->willReturn(10);
        $list->getItemsPerPage()->willReturn(10);
        $list->countTotal()->willReturn(25);

        $this->hasNextPage($list)->shouldReturn(true);
    }

    function it_returns_false_if_there_is_no_next_page(PaginatableResultsInterface $list)
    {
        $list->getOffset()->willReturn(10);
        $list->getItemsPerPage()->willReturn(10);
        $list->countTotal()->willReturn(20);

        $this->hasNextPage($list)->shouldReturn(false);
    }

    function it_returns_true_for_previous_page_if_current_page_is_not_the_first(PaginatableResultsInterface $list)
    {
        $this->hasPreviousPage($list, 2)->shouldReturn(true);
    }

    function it_returns_false_for_previous_page_if_current_page_is_the_first(PaginatableResultsInterface $list)
    {
        $this->hasPreviousPage($list, 1)->shouldReturn(false);
    }
}
