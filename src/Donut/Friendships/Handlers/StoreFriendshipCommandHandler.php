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

namespace Angelov\Donut\Friendships\Handlers;

use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\Repositories\FriendshipsRepositoryInterface;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class StoreFriendshipCommandHandler
{
    private $friendships;
    private $users;
    private $eventBus;

    public function __construct(
        FriendshipsRepositoryInterface $friendships,
        UsersRepositoryInterface $users,
        EventBusInterface $eventBus
    ) {
        $this->friendships = $friendships;
        $this->eventBus = $eventBus;
        $this->users = $users;
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function handle(StoreFriendshipCommand $command) : void
    {
        $user = $this->users->find($command->getUserId());
        $friend = $this->users->find($command->getFriendId());

        $friendship = new Friendship($command->getId(), $user, $friend);

        $this->friendships->store($friendship);

        $this->eventBus->fire(new FriendshipWasCreatedEvent($friendship));
    }
}
