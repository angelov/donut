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

namespace spec\Angelov\Donut\Thoughts\ThoughtsCounter;

use Exception;
use Predis\Client;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use Angelov\Donut\Thoughts\ThoughtsCounter\RedisThoughtsCounter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Angelov\Donut\Users\User;

class RedisThoughtsCounterSpec extends ObjectBehavior
{
    function let(Client $redisClient, User $user)
    {
        $this->beConstructedWith($redisClient);

        $user->getId()->willReturn('2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RedisThoughtsCounter::class);
    }

    function it_is_thoughts_counter()
    {
        $this->shouldImplement(ThoughtsCounterInterface::class);
    }

    function it_increases_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->incr(Argument::type('string'))->shouldBeCalled();

        $this->increase($user);
    }

    function it_decreases_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(2);

        $redisClient->decr(Argument::type('string'))->shouldBeCalled();

        $this->decrease($user);
    }

    function it_does_not_decrease_bellow_zero(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(0);

        $redisClient->decr(Argument::type('string'))->shouldNotBeCalled();

        $this->decrease($user);
    }

    function it_returns_zero_if_user_has_no_shared_thoughts(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->count($user)->shouldReturn(0);
    }

    function it_returns_number_of_thoughts_for_user(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->shouldBeCalled()->willReturn('2');

        $this->count($user)->shouldReturn(2);
    }

    function it_throws_exception_when_something_is_wrong_with_redis(User $user, Client $redisClient)
    {
        $redisClient->get(Argument::type('string'))->willThrow(Exception::class);

        $this->shouldThrow(CouldNotCountThoughtsForUserException::class)->during('count', [$user]);
    }
}
