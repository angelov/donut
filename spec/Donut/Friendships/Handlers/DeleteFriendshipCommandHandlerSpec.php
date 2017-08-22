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
    public function let(FriendshipsRepositoryInterface $repository, EventBusInterface $eventBus)
    {
        $this->beConstructedWith($repository, $eventBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteFriendshipCommandHandler::class);
    }

    function it_handles_delete_friendship_commands(
        DeleteFriendshipCommand $command,
        Friendship $friendship,
        FriendshipsRepositoryInterface $repository,
        EventBusInterface $eventBus
    ) {
        $command->getFriendship()->willReturn($friendship);

        $repository->destroy($friendship)->shouldBeCalled();

        $eventBus->fire(Argument::type(FriendshipWasDeletedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
