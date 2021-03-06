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

use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class SendFriendshipRequestCommandHandler
{
    private $friendshipRequests;
    private $users;

    public function __construct(FriendshipRequestsRepositoryInterface $friendshipRequests, UsersRepositoryInterface $users)
    {
        $this->friendshipRequests = $friendshipRequests;
        $this->users = $users;
    }

    public function handle(SendFriendshipRequestCommand $command) : void
    {
        $sender = $this->users->find($command->getSenderId());
        $recipient = $this->users->find($command->getRecipientId());

        if ($sender->equals($recipient)) {
            // @todo more concrete exception
            throw new \Exception();
        }

        $friendshipRequest = new FriendshipRequest($command->getId(), $sender, $recipient);

        $this->friendshipRequests->store($friendshipRequest);
    }
}
