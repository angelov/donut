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
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Handlers\DeclineFriendshipRequestCommandHandler;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use PhpSpec\ObjectBehavior;

class DeclineFriendshipRequestCommandHandlerSpec extends ObjectBehavior
{
    function let(FriendshipRequestsRepositoryInterface $requests, DeclineFriendshipRequestCommand $command)
    {
        $this->beConstructedWith($requests);

        $command->getFriendshipRequestId()->willReturn('request id');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeclineFriendshipRequestCommandHandler::class);
    }

    function it_throws_exception_if_the_request_is_not_found(
        FriendshipRequestsRepositoryInterface $requests,
        DeclineFriendshipRequestCommand $command
    ) {
        $requests->find('request id')->shouldBeCalled()->willThrow(ResourceNotFoundException::class);

        $this->shouldThrow(ResourceNotFoundException::class)->during('handle', [$command]);
    }

    function it_deletes_friendship_requests(
        DeclineFriendshipRequestCommand $command,
        FriendshipRequest $request,
        FriendshipRequestsRepositoryInterface $requests
    ) {
        $requests->find('request id')->shouldBeCalled()->willReturn($request);

        $requests->destroy($request)->shouldBeCalled();

        $this->handle($command);
    }
}
