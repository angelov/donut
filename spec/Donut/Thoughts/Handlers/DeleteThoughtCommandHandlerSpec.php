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

namespace spec\Angelov\Donut\Thoughts\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Thoughts\Commands\DeleteThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use Angelov\Donut\Thoughts\Handlers\DeleteThoughtCommandHandler;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;
use Angelov\Donut\Thoughts\Thought;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteThoughtCommandHandlerSpec extends ObjectBehavior
{
    const THOUGHT_ID = 't id';

    function let(ThoughtsRepositoryInterface $thoughts, EventBusInterface $events, DeleteThoughtCommand $command)
    {
        $this->beConstructedWith($thoughts, $events);

        $command->getThoughtId()->willReturn(self::THOUGHT_ID);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteThoughtCommandHandler::class);
    }

    function it_throws_exception_if_the_thought_is_not_found(ThoughtsRepositoryInterface $thoughts, DeleteThoughtCommand $command)
    {
        $thoughts->find(self::THOUGHT_ID)->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_deletes_the_given_thought(
        DeleteThoughtCommand $command,
        Thought $thought,
        ThoughtsRepositoryInterface $thoughts,
        EventBusInterface $events
    ) {
        $command->getThoughtId()->shouldBeCalled();
        $thoughts->find(self::THOUGHT_ID)->shouldBeCalled()->willReturn($thought);

        $thoughts->destroy($thought)->shouldBeCalled();

        $events->fire(Argument::type(ThoughtWasDeletedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
