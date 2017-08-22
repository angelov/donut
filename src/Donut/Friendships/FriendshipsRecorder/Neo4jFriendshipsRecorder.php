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

namespace Angelov\Donut\Friendships\FriendshipsRecorder;

use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Users\User;

class Neo4jFriendshipsRecorder implements FriendshipsRecorderInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function recordCreated(Friendship $friendship): void
    {
        // @todo handle neo4j exceptions

        $user = $friendship->getUser();
        $friend = $friendship->getFriend();

        $this->createUserNode($user);
        $this->createUserNode($friend);

        $this->createFriendshipRelationships($user, $friend);
    }

    private function createUserNode(User $user) : void
    {
        $query = 'MERGE (n:User {id: {id}, name: {name}})';

        $this->client->run($query, ['id' => $user->getId(), 'name' => $user->getName()]);
    }

    private function createFriendshipRelationships(User $first, User $second) : void
    {
        $query = '
            MATCH
                (first:User {id:{first}}),
                (second:User {id:{second}})
            CREATE
                (first)-[:FRIEND]->(second)
        ';

        $this->client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);
    }

    public function recordDeleted(Friendship $friendship): void
    {
        // @todo handle exceptions

        $first = $friendship->getUser();
        $second = $friendship->getFriend();

        $query = '
            MATCH 
                (u:User {id: {first}}), 
                (r:User {id: {second}}),
                (u)-[f:FRIEND]-(r)
            DELETE f
        ';

        $this->client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);
    }
}
