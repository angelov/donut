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

namespace spec\Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker;

use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceChecker;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceCheckerInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;

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
