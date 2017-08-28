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

namespace spec\Angelov\Donut\Thoughts;

use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Users\User;
use PhpSpec\ObjectBehavior;

class ThoughtSpec extends ObjectBehavior
{
    const THOUGHT_ID = 'uuid value';
    const THOUGHT_CONTENT = 'example';

    function let(User $user)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $user, self::THOUGHT_CONTENT);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Thought::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::THOUGHT_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new id');
        $this->getId()->shouldReturn('new id');
    }

    function it_has_content_by_default()
    {
        $this->getContent()->shouldReturn(self::THOUGHT_CONTENT);
    }

    function it_has_mutable_content()
    {
        $this->setContent('example 2');
        $this->getContent()->shouldReturn('example 2');
    }

    function it_has_created_at_date_by_default()
    {
        $this->getCreatedAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    function it_has_mutable_created_at_date(\DateTime $dateTime)
    {
        $this->setCreatedAt($dateTime);
        $this->getCreatedAt()->shouldReturn($dateTime);
    }

    function it_can_be_constructed_with_created_at_date(User $user, \DateTime $dateTime)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $user, self::THOUGHT_CONTENT, $dateTime);

        $this->getCreatedAt()->shouldReturn($dateTime);
    }

    function it_has_author_by_default(User $user)
    {
        $this->getAuthor()->shouldReturn($user);
    }

    function it_has_mutable_author(User $user2)
    {
        $this->setAuthor($user2);
        $this->getAuthor()->shouldReturn($user2);
    }
}
