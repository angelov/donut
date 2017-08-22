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

namespace Angelov\Donut\Users\EmailAvailabilityChecker;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Angelov\Donut\Users\Repositories\UsersRepositoryInterface;

class EmailAvailabilityChecker implements EmailAvailabilityCheckerInterface
{
    private $users;

    public function __construct(UsersRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function isTaken(string $email): bool
    {
        try {
            $this->users->findByEmail($email);
            return true;
        } catch (ResourceNotFoundException $exception) {
            return false;
        }
    }
}
