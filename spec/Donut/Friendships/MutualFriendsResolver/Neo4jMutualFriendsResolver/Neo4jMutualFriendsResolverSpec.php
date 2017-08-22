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

namespace spec\Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use Angelov\Donut\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;
use Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\IdsResolver;
use Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\Neo4jMutualFriendsResolver;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use Angelov\Donut\Users\User;

class Neo4jMutualFriendsResolverSpec extends ObjectBehavior
{
    function let(IdsResolver $idsResolver, UsersProviderInterface $usersProvider)
    {
        $this->beConstructedWith($idsResolver, $usersProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Neo4jMutualFriendsResolver::class);
    }

    function it_is_mutual_friends_resolver()
    {
        $this->shouldImplement(MutualFriendsResolverInterface::class);
    }

    function it_returns_empty_array_if_same_user_is_submitted_twice(User $first, User $second)
    {
        $first->equals($second)->willReturn(true);

        $this->forUsers($first, $second);
    }

    function it_returns_empty_array_if_there_are_no_mutual_friends(IdsResolver $idsResolver, User $first, User $second)
    {
        $first->equals($second)->willReturn(false);
        $first->getId()->willReturn('1');
        $second->getId()->willReturn('2');

        $idsResolver->findMutualFriends('1', '2')->willReturn([]);

        $this->forUsers($first, $second)->shouldReturn([]);
    }

    function it_returns_array_of_users_if_mutual_friends_are_found(
        IdsResolver $idsResolver,
        User $first,
        User $second,
        User $mutual1,
        User $mutual2,
        UsersProviderInterface $usersProvider
    ) {
        $first->equals($second)->willReturn(false);
        $first->getId()->willReturn('1');
        $second->getId()->willReturn('2');

        $idsResolver->findMutualFriends('1', '2')->willReturn(['3', '4']);

        $usersProvider->getById('3')->willReturn($mutual1);
        $usersProvider->getById('4')->willReturn($mutual2);

        $this->forUsers($first, $second)->shouldReturn([$mutual1, $mutual2]);
    }
}
