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

namespace Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use Angelov\Donut\Friendships\MutualFriendsResolver\MutualFriendsResolverInterface;
use Angelov\Donut\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use Angelov\Donut\Users\User;

class Neo4jMutualFriendsResolver implements MutualFriendsResolverInterface
{
    private $usersProvider;
    private $idsResolver;

    public function __construct(IdsResolver $idsResolver, UsersProviderInterface $usersProvider)
    {
        $this->usersProvider = $usersProvider;
        $this->idsResolver = $idsResolver;
    }

    /**
     * @return User[]
     */
    public function forUsers(User $first, User $second): array
    {
        if ($first->equals($second)) {
            return [];
        }

        $ids = $this->idsResolver->findMutualFriends($first->getId(), $second->getId());

        $users = [];

        foreach ($ids as $id) {
            /** @var User */
            $users[] = $this->usersProvider->getById($id);
        }

        return $users;
    }
}
