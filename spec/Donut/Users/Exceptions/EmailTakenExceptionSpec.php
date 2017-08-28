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

namespace spec\Angelov\Donut\Users\Exceptions;

use Angelov\Donut\Users\Exceptions\EmailTakenException;
use PhpSpec\ObjectBehavior;

class EmailTakenExceptionSpec extends ObjectBehavior
{
    const TAKEN_EMAIL = 'john@example.com';

    function let()
    {
        $this->beConstructedWith(self::TAKEN_EMAIL);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmailTakenException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }

    function it_contains_the_email_address()
    {
        $this->getEmail()->shouldReturn(self::TAKEN_EMAIL);
    }

    function it_has_a_message_by_default()
    {
        $this->getMessage()->shouldReturn(sprintf(
            'The provided email address [%s] is already in use',
            self::TAKEN_EMAIL
        ));
    }
}
