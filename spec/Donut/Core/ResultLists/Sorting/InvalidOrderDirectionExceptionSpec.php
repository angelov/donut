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

namespace spec\Angelov\Donut\Core\ResultLists\Sorting;

use Exception;
use Angelov\Donut\Core\ResultLists\Sorting\InvalidOrderDirectionException;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\ResultLists\Sorting\OrderDirection;

class InvalidOrderDirectionExceptionSpec extends ObjectBehavior
{
    const INVALID_ORDER_DIRECTION = 'BFS';

    function let()
    {
        $this->beConstructedWith(self::INVALID_ORDER_DIRECTION);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InvalidOrderDirectionException::class);
    }

    function it_is_exception()
    {
        $this->shouldBeAnInstanceOf(Exception::class);
    }

    function it_holds_the_order_direction()
    {
        $this->getDirection()->shouldReturn(self::INVALID_ORDER_DIRECTION);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(sprintf(
            '%s is not a valid ordering direction. Please check the "%s" class for valid directions.',
            self::INVALID_ORDER_DIRECTION,
            OrderDirection::class
        ));
    }
}
