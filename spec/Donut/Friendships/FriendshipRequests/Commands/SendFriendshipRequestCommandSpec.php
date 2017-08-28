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

namespace spec\Angelov\Donut\Friendships\FriendshipRequests\Commands;

use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class SendFriendshipRequestCommandSpec extends ObjectBehavior
{
    const FRIENDSHIP_REQUEST_ID = 'uuid value';
    const USER_ID = 'u id';
    const FRIEND_ID = 'f id';

    function let()
    {
        $this->beConstructedWith(self::FRIENDSHIP_REQUEST_ID, self::USER_ID, self::FRIEND_ID);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendFriendshipRequestCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::FRIENDSHIP_REQUEST_ID);
    }

    function it_holds_the_sender_id()
    {
        $this->getSenderId()->shouldReturn(self::USER_ID);
    }

    function it_holds_the_recipient()
    {
        $this->getRecipientId()->shouldReturn(self::FRIEND_ID);
    }
}
