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

namespace spec\Angelov\Donut\Thoughts\ThoughtsCounter;

use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use Angelov\Donut\Thoughts\ThoughtsCounter\LoggingThoughtsCounter;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Angelov\Donut\Users\User;

class LoggingThoughtsCounterSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $inner, LoggerInterface $logger)
    {
        $this->beConstructedWith($inner, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LoggingThoughtsCounter::class);
    }

    function it_is_thoughts_counter()
    {
        $this->shouldImplement(ThoughtsCounterInterface::class);
    }

    function it_passes_increase_calls_to_decorated_counter(ThoughtsCounterInterface $inner, User $user)
    {
        $inner->increase($user)->shouldBeCalled();

        $this->increase($user);
    }

    function it_passes_decrease_calls_to_decorated_counter(ThoughtsCounterInterface $inner, User $user)
    {
        $inner->decrease($user)->shouldBeCalled();

        $this->decrease($user);
    }

    function it_logs_exceptions_from_decorated_counter_when_counting(ThoughtsCounterInterface $inner, User $user, LoggerInterface $logger)
    {
        $inner->count($user)->willThrow(CouldNotCountThoughtsForUserException::class);

        $logger->alert(Argument::type('string'))->shouldBeCalled();

        $this->shouldThrow(CouldNotCountThoughtsForUserException::class)->during('count', [$user]);
    }
}
