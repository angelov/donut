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

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Handlers\StoreFriendshipCommandHandler;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;
use Angelov\Donut\Users\User;

class StoreFriendshipCommandHandlerSpec extends ObjectBehavior
{
    const USER_ID = 'u id';
    const FRIEND_ID = 'f id';
    const FRIENDSHIP_ID = 'uuid value';

    function let(
        FriendshipsRepositoryInterface $friendships,
        UsersRepositoryInterface $users,
        EventBusInterface $eventBus,
        StoreFriendshipCommand $command,
        User $user,
        User $friend
    ) {
        $this->beConstructedWith($friendships, $users, $eventBus);

        $command->getId()->willReturn(self::FRIENDSHIP_ID);
        $command->getUserId()->willReturn(self::USER_ID);
        $command->getFriendId()->willReturn(self::FRIEND_ID);

        $users->find(self::USER_ID)->willReturn($user);
        $users->find(self::FRIEND_ID)->willReturn($friend);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreFriendshipCommandHandler::class);
    }

    function it_throws_exception_if_the_user_is_not_found(UsersRepositoryInterface $users, StoreFriendshipCommand $command)
    {
        $users->find(self::USER_ID)->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_throws_exception_if_the_friend_is_not_found(UsersRepositoryInterface $users, StoreFriendshipCommand $command)
    {
        $users->find(self::FRIEND_ID)->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_stores_friendships(
        StoreFriendshipCommand $command,
        FriendshipsRepositoryInterface $friendships,
        EventBusInterface $eventBus
    ) {
        $friendships->store(Argument::type(Friendship::class))->shouldBeCalled();
        $eventBus->fire(Argument::type(FriendshipWasCreatedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
