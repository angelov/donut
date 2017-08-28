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

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\SendFriendshipRequestCommandHandler;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\User;

class SendFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    const FRIENDSHIP_REQUEST_ID = 'uuid value';

    function let(
        FriendshipRequestsRepositoryInterface $repository,
        UsersRepositoryInterface $users,
        SendFriendshipRequestCommand $command,
        User $sender,
        User $recipient
    ) {
        $this->beConstructedWith($repository, $users);

        $command->getId()->willReturn(self::FRIENDSHIP_REQUEST_ID);

        $command->getSenderId()->willReturn('sender id');
        $users->find('sender id')->willReturn($sender);

        $command->getRecipientId()->willReturn('recipient id');
        $users->find('recipient id')->willReturn($recipient);

        $sender->equals($recipient)->willReturn(false);
        $recipient->equals($sender)->willReturn(false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommandHandler::class);
    }

    function it_throws_exception_when_the_sender_is_not_found(
        UsersRepositoryInterface $users,
        SendFriendshipRequestCommand $command
    ) {
        $users->find('sender id')->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_throws_exception_when_the_recipient_is_not_found(
        UsersRepositoryInterface $users,
        SendFriendshipRequestCommand $command
    ) {
        $users->find('recipient id')->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_throws_exception_when_the_sender_and_recipient_are_same_person(
        User $sender,
        User $recipient,
        SendFriendshipRequestCommand $command
    ) {
        $sender->equals($recipient)->shouldBeCalled()->willReturn(true);

        // @todo throw more specific exception
        $this->shouldThrow(\Exception::class)->during('handle', [$command]);
    }

    function it_stores_new_friendship_requests(
        SendFriendshipRequestCommand $command,
        UsersRepositoryInterface $users,
        User $sender,
        User $recipient,
        FriendshipRequestsRepositoryInterface $repository
    ) {
        $command->getRecipientId()->shouldBeCalled();
        $command->getSenderId()->shouldBeCalled();
        $command->getId()->shouldBeCalled();

        $users->find('sender id')->shouldBeCalled()->willReturn($sender);
        $users->find('recipient id')->shouldBeCalled()->willReturn($recipient);

        $sender->addSentFriendshipRequest(Argument::type(FriendshipRequest::class))->shouldBeCalled();
        $recipient->addReceivedFriendshipRequest(Argument::type(FriendshipRequest::class))->shouldBeCalled();

        $repository->store(Argument::type(FriendshipRequest::class))->shouldBeCalled();

        $this->handle($command);
    }
}
