<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2019, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2019, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Donut\Thoughts\ThoughtsCounter;

use Angelov\Donut\Tests\Donut\TestCase;
use AppBundle\Factories\UsersFactory;
use Predis\Client;
use Angelov\Donut\Thoughts\ThoughtsCounter\RedisThoughtsCounter;

class RedisThoughtsCounterTest extends TestCase
{
    /** @var Client */
    private $redisClient;

    /** @var RedisThoughtsCounter */
    private $counter;

    /** @var UsersFactory */
    private $usersFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redisClient = $this->getService(Client::class);
        $this->counter = $this->getService(RedisThoughtsCounter::class);
        $this->usersFactory = $this->getService(UsersFactory::class);
    }

    /** @test */
    public function it_increases_number_of_thoughts_for_first_time_publishers()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_increases_number_of_thoughts_for_existing_users()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->redisClient->get('user_thoughts_' . $user->getId());

        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_decreases_number_of_thougts_for_users()
    {
        $user = $this->usersFactory->get();

        $this->redisClient->set('user_thoughts_'. $user->getId(), 3);

        $this->counter->decrease($user);

        $count = $this->counter->count($user);

        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_fetches_number_of_thougts_for_user()
    {
        $user = $this->usersFactory->get();

        $this->counter->increase($user);
        $this->counter->increase($user);

        $count = $this->counter->count($user);

        $this->assertEquals(2, $count);
    }
}
