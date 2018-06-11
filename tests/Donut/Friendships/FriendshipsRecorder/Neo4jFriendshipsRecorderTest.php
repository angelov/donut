<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Friendships\FriendshipsRecorder;

use Angelov\Donut\Tests\Donut\TestCase;
use AppBundle\Factories\FriendshipsFactory;
use AppBundle\Factories\UsersFactory;
use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Friendships\FriendshipsRecorder\Neo4jFriendshipsRecorder;

class Neo4jFriendshipsRecorderTest extends TestCase
{
    /** @var Neo4jFriendshipsRecorder */
    private $recorder;

    /** @var Client */
    private $client;

    /** @var FriendshipsFactory */
    private $friendshipsFactory;

    /** @var $usersFactory UsersFactory */
    private $usersFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->recorder = $this->getService(Neo4jFriendshipsRecorder::class);
        $this->client = $this->getService(Client::class);
        $this->friendshipsFactory = $this->getService(FriendshipsFactory::class);
        $this->usersFactory = $this->getService(UsersFactory::class);
    }

    /** @test */
    public function it_stores_friendships_in_neo4j()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $friendship = $this->friendshipsFactory->from($sender)->to($recipient)->get();

        $this->recorder->recordCreated($friendship);

        $query = '
            MATCH
                (first: User {id: {first}}),
                (second: User {id: {second}}),
                (first)-[f: FRIEND]->(second)
            RETURN f
        ';

        $result = $this->client->run($query, [
            'first' => $sender->getId(),
            'second' => $recipient->getId()
        ]);

        $records = $result->records();

        $this->assertCount(1, $records);
    }

    /** @test */
    public function it_removed_friendships_from_neo4j()
    {
        $sender = $this->usersFactory->get();
        $recipient = $this->usersFactory->get();

        $friendship = $this->friendshipsFactory->from($sender)->to($recipient)->get();

        $this->recorder->recordCreated($friendship);

        $this->recorder->recordDeleted($friendship);

        $query = '
            MATCH
                (first: User {id: {first}}),
                (second: User {id: {second}}),
                (first)-[f: FRIEND]-(second)
            RETURN f
        ';

        $result = $this->client->run($query, [
            'first' => $sender->getId(),
            'second' => $recipient->getId()
        ]);

        $records = $result->records();

        $this->assertCount(0, $records);
    }
}
