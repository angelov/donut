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

namespace spec\Angelov\Donut\Friendships;

use Angelov\Donut\Friendships\Friendship;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Users\User;

class FriendshipSpec extends ObjectBehavior
{
    const FRIENDSHIP_ID = 'uuid value';

    function let(User $user, User $friend)
    {
        $this->beConstructedWith(self::FRIENDSHIP_ID, $user, $friend);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Friendship::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::FRIENDSHIP_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new');
        $this->getId()->shouldReturn('new');
    }

    function it_has_user_by_default(User $user)
    {
        $this->getUser()->shouldReturn($user);
    }

    function it_has_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_has_friend_by_default(User $friend)
    {
        $this->getFriend()->shouldReturn($friend);
    }

    function it_has_mutable_friend(User $user)
    {
        $this->setFriend($user);
        $this->getFriend()->shouldReturn($user);
    }

    function it_has_created_at_date_by_default()
    {
        $this->getCreatedAt()->shouldReturnAnInstanceOf(\DateTime::class);
    }

    function it_has_mutable_created_at_date(\DateTime $createdAt)
    {
        $this->setCreatedAt($createdAt);
        $this->getCreatedAt()->shouldReturn($createdAt);
    }
}
