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

namespace Angelov\Donut\Thoughts\ThoughtsCounter;

use Predis\Client;
use Angelov\Donut\Thoughts\ThoughtsCounter\Exceptions\CouldNotCountThoughtsForUserException;
use Angelov\Donut\Users\User;

class RedisThoughtsCounter implements ThoughtsCounterInterface
{
    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function increase(User $user) : void
    {
        $this->redisClient->incr($this->resolveKey($user));
    }

    public function decrease(User $user): void
    {
        $key = $this->resolveKey($user);

        if ($this->count($user) !== 0) {
            $this->redisClient->decr($key);
        }
    }

    public function count(User $user): int
    {
        try {
            $count = $this->redisClient->get($this->resolveKey($user));
        } catch (\Exception $exception) {
            throw new CouldNotCountThoughtsForUserException($user, $exception->getMessage());
        }

        return $count ? (int) $count : 0;
    }

    private function resolveKey(User $user) : string
    {
        return sprintf('user_thoughts_%s', $user->getId());
    }
}
