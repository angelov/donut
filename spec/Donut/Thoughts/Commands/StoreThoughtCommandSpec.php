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

namespace spec\Angelov\Donut\Thoughts\Commands;

use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class StoreThoughtCommandSpec extends ObjectBehavior
{
    const THOUGHT_CONTENT = 'something to say';
    const THOUGHT_ID = 'uuid value';

    public function let(User $author)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $author, self::THOUGHT_CONTENT);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreThoughtCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::THOUGHT_ID);
    }

    function it_holds_the_author(User $author)
    {
        $this->getAuthor()->shouldReturn($author);
    }

    function it_holds_the_content()
    {
        $this->getContent()->shouldReturn(self::THOUGHT_CONTENT);
    }

    function it_can_hold_the_created_at_date(User $author, \DateTime $dateTime)
    {
        $this->beConstructedWith(self::THOUGHT_ID, $author, self::THOUGHT_CONTENT, $dateTime);

        $this->getCreatedAt()->shouldReturn($dateTime);
    }
}
