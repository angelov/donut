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
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\AcceptFriendshipRequestCommandHandler;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\User;

class AcceptFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(
        FriendshipRequestsRepositoryInterface $requestsRepository,
        CommandBusInterface $commandBus,
        UuidGeneratorInterface $uuidGenerator,
        EventBusInterface $eventBus,
        AcceptFriendshipRequestCommand $command
    ) {
        $this->beConstructedWith($requestsRepository, $uuidGenerator, $commandBus, $eventBus);

        $command->getFriendshipRequestId()->willReturn('request id');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AcceptFriendshipRequestCommandHandler::class);
    }

    function it_throws_exception_if_the_request_is_not_found(
        FriendshipRequestsRepositoryInterface $requestsRepository,
        AcceptFriendshipRequestCommand $command
    ) {
        $requestsRepository->find('request id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_creates_new_frienship_when_the_request_is_accepted(
        AcceptFriendshipRequestCommand $command,
        FriendshipRequest $request,
        User $sender,
        User $recipient,
        FriendshipRequestsRepositoryInterface $requestsRepository,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus
    ) {
        $requestsRepository->find('request id')->shouldBeCalled()->willReturn($request);

        $request->getFromUser()->willReturn($sender);
        $request->getToUser()->willReturn($recipient);

        $sender->getId()->willReturn('sender id');
        $recipient->getId()->willReturn('recipient id');

        $requestsRepository->destroy($request)->shouldBeCalled();

        $commandBus->handle(Argument::type(StoreFriendshipCommand::class))->shouldBeCalledTimes(2);
        $uuidGenerator->generate()->shouldBeCalledTimes(2);

        $eventBus->fire(Argument::type(FriendshipRequestWasAcceptedEvent::class))->shouldBeCalled();

        $this->handle($command);
    }
}
