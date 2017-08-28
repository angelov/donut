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

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use Angelov\Donut\Users\User;
use Prophecy\Argument;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Thoughts\Commands\StoreThoughtCommand;
use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\Handlers\StoreThoughtCommandHandler;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Repositories\ThoughtsRepositoryInterface;
use Angelov\Donut\Thoughts\Thought;

class StoreThoughtCommandHandlerSpec extends ObjectBehavior
{
    function let(ThoughtsRepositoryInterface $repository, UsersRepositoryInterface $users, EventBusInterface $events)
    {
        $this->beConstructedWith($repository, $users, $events);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreThoughtCommandHandler::class);
    }

    function it_throws_exception_if_the_author_is_not_found(UsersRepositoryInterface $users, StoreThoughtCommand $command)
    {
        $command->getAuthorId()->shouldBeCalled()->willReturn('author id');
        $users->find('author id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_stores_the_new_thought(
        StoreThoughtCommand $command,
        ThoughtsRepositoryInterface $repository,
        UsersRepositoryInterface $users,
        User $author,
        EventBusInterface $events
    ) {
        $command->getAuthorId()->shouldBeCalled()->willReturn('author id');

        $users->find('author id')->shouldBeCalled()->willReturn($author);

        $command->getContent()->shouldBeCalled();
        $command->getId()->shouldBeCalled();
        $command->getCreatedAt()->shouldBeCalled();

        $repository->store(Argument::type(Thought::class))->shouldBeCalled();

        $events->fire(Argument::type(ThoughtWasPublishedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
