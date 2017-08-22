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

namespace Angelov\Donut\Friendships\FriendshipRequests\Handlers;

use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Events\FriendshipRequestWasAcceptedEvent;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;

class AcceptFriendshipRequestCommandHandler
{
    private $requests;
    private $events;
    private $uuidGenerator;
    private $commandBus;

    public function __construct(
        FriendshipRequestsRepositoryInterface $requests,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus,
        EventBusInterface $events
    ) {
        $this->requests = $requests;
        $this->events = $events;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
    }

    public function handle(AcceptFriendshipRequestCommand $command) : void
    {
        $request = $command->getFriendshipRequest();
        $sender = $request->getFromUser();
        $recipient = $request->getToUser();

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $sender, $recipient));

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $recipient, $sender));

        $this->requests->destroy($request);

        $this->events->fire(new FriendshipRequestWasAcceptedEvent($request));
    }
}
