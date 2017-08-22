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

use GraphAware\Neo4j\Client\Client;

class IdsResolver
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string[] array of ids of the mutual friends
     */
    public function findMutualFriends(string $firstId, string $secondId) : array
    {
        $query = '
            MATCH
                (first:User {id: {first}}),
                (second:User {id: {second}}),
                (y:User),
                (first)-[:FRIEND]->(y),
                (y)<-[:FRIEND]-(second)
            RETURN
                y.id
        ';

        $result = $this->client->run($query, [
            'first' => $firstId,
            'second' => $secondId
        ]);

        $ids = [];

        foreach ($result->records() as $record) {
            $ids[] = (string) $record->get('y.id');
        }

        return $ids;
    }
}
