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

namespace spec\Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions;

use RuntimeException;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class CouldNotCountThoughtsForUserExceptionSpec extends ObjectBehavior
{
    const REASON_MESSAGE = 'Something went wrong';

    function let(User $user)
    {
        $this->beConstructedWith($user, self::REASON_MESSAGE);

        $user->getId()->willReturn('2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CouldNotCountThoughtsForUserException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(RuntimeException::class);
    }

    function it_contains_the_user(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_contains_the_reason()
    {
        $this->getReason()->shouldReturn(self::REASON_MESSAGE);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(
            'Could not fetch number of thoughts for user [2]: '. self::REASON_MESSAGE
        );
    }
}
