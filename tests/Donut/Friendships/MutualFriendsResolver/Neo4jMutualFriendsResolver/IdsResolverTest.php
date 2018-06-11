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

namespace Angelov\Donut\Tests\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use Angelov\Donut\Tests\Donut\TestCase;
use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\IdsResolver;

class IdsResolverTest extends TestCase
{
    /** @var Client */
    private $client;

    /** @var IdsResolver */
    private $resolver;

    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->getService(Client::class);
        $this->resolver = $this->getService(IdsResolver::class);

        $this->client->run('MATCH (n) DETACH DELETE n');
    }

    /** @test */
    public function it_returns_empty_array_when_there_are_no_mutual_friends()
    {
        $mutual = $this->resolver->findMutualFriends('1', '2');

        $this->assertEquals([], $mutual);
    }

    /** @test */
    public function it_returns_array_of_mutual_friends_ids()
    {
        $this->client->run(
            'CREATE (a: User {id: "1"}),
                    (b: User {id: "2"}),
                    (c: User {id: "3"}),
                    (d: User {id: "4"}),
                    
                    (a)-[:FRIEND]->(b),
                    (a)<-[:FRIEND]-(b),
                    
                    (a)-[:FRIEND]->(c),
                    (a)<-[:FRIEND]-(c),

                    (d)-[:FRIEND]->(b),
                    (d)<-[:FRIEND]-(b),

                    (d)-[:FRIEND]->(c),
                    (d)<-[:FRIEND]-(c)
            '
        );

        $mutual = $this->resolver->findMutualFriends('1', '4');

        $this->assertCount(2, $mutual);
        $this->assertContains('2', $mutual);
        $this->assertContains('3', $mutual);
    }

    // @todo test neo4j exceptions handling
}
