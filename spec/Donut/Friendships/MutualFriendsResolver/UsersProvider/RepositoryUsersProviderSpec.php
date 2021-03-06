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

namespace spec\Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider;

use Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider\RepositoryUsersProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class RepositoryUsersProviderSpec extends ObjectBehavior
{
    function let(UsersRepositoryInterface $users)
    {
        $this->beConstructedWith($users);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RepositoryUsersProvider::class);
    }

    function it_is_users_provider()
    {
        $this->shouldImplement(UsersProviderInterface::class);
    }

    function it_passes_get_by_id_calls_to_repository(UsersRepositoryInterface $users)
    {
        $users->find('5')->shouldBeCalled();

        $this->getById('5');
    }
}
