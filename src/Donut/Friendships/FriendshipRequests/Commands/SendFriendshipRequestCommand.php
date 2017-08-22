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

namespace Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Users\User;

class SendFriendshipRequestCommand
{
    private $id;
    private $sender;
    private $recipient;

    public function __construct(string $id, User $sender, User $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSender() : User
    {
        return $this->sender;
    }

    public function getRecipient() : User
    {
        return $this->recipient;
    }
}
