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

use Psr\Log\LoggerInterface;
use Angelov\Donut\Users\User;

class LoggingThoughtsCounter implements ThoughtsCounterInterface
{
    private $decorated;
    private $logger;

    public function __construct(ThoughtsCounterInterface $decorated, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->logger = $logger;
    }

    public function increase(User $user): void
    {
        $this->decorated->increase($user);
    }

    public function decrease(User $user): void
    {
        $this->decorated->decrease($user);
    }

    public function count(User $user): int
    {
        try {
            return $this->decorated->count($user);
        } catch (\Exception $exception) {
            $this->logger->alert($exception->getMessage());

            throw $exception;
        }
    }
}
