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

namespace spec\Angelov\Donut\Thoughts\EventSubscribers;

use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\EventSubscribers\IncreaseThoughtsNumberForUser;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Angelov\Donut\Users\User;

class IncreaseThoughtsNumberForUserSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $counter)
    {
        $this->beConstructedWith($counter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IncreaseThoughtsNumberForUser::class);
    }

    function it_handles_thought_was_published_events(
        ThoughtsCounterInterface $counter,
        ThoughtWasPublishedEvent $event,
        Thought $thought,
        User $user
    ) {
        $event->getThought()->willReturn($thought);
        $thought->getAuthor()->willReturn($user);

        $counter->increase($user)->shouldBeCalled();

        $this->notify($event);
    }
}
