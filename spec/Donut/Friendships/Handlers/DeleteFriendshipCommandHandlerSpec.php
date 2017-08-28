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

namespace spec\Angelov\Donut\Friendships\Handlers;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Handlers\DeleteFriendshipCommandHandler;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;

class DeleteFriendshipCommandHandlerSpec extends ObjectBehavior
{
    public function let(FriendshipsRepositoryInterface $friendships, EventBusInterface $eventBus, DeleteFriendshipCommand $command)
    {
        $this->beConstructedWith($friendships, $eventBus);

        $command->getFriendshipId()->willReturn('friendship id');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteFriendshipCommandHandler::class);
    }

    function it_throws_exception_when_the_friendship_is_not_found(
        FriendshipsRepositoryInterface $friendships,
        DeleteFriendshipCommand $command
    ) {
        $friendships->find('friendship id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_deletes_the_friendship(
        DeleteFriendshipCommand $command,
        Friendship $friendship,
        FriendshipsRepositoryInterface $friendships,
        EventBusInterface $eventBus
    ) {
        $friendships->find('friendship id')->shouldBeCalled()->willReturn($friendship);

        $friendships->destroy($friendship)->shouldBeCalled();

        $eventBus->fire(Argument::type(FriendshipWasDeletedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
