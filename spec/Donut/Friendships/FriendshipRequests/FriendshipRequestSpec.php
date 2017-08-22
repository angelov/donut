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

namespace spec\Angelov\Donut\Friendships\FriendshipRequests;

use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class FriendshipRequestSpec extends ObjectBehavior
{
    const REQUEST_ID = 'uuid value';

    function let(User $sender, User $receiver)
    {
        $this->beConstructedWith(self::REQUEST_ID, $sender, $receiver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FriendshipRequest::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::REQUEST_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new value');
        $this->getId()->shouldReturn('new value');
    }

    function it_holds_the_sender(User $sender)
    {
        $this->getFromUser()->shouldReturn($sender);
    }

    function it_holds_the_receiver(User $receiver)
    {
        $this->getToUser()->shouldReturn($receiver);
    }

    function it_has_mutable_sender(User $anotherSender)
    {
        $this->setFromUser($anotherSender);
        $this->getFromUser()->shouldReturn($anotherSender);
    }

    function it_has_mutable_receiver(User $anotherReceiver)
    {
        $this->setToUser($anotherReceiver);
        $this->getToUser()->shouldReturn($anotherReceiver);
    }
}
