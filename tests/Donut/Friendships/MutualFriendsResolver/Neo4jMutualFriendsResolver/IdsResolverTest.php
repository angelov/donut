<?php

namespace Angelov\Donut\Tests\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver;

use GraphAware\Neo4j\Client\Client;
use Angelov\Donut\Friendships\MutualFriendsResolver\Neo4jMutualFriendsResolver\IdsResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IdsResolverTest extends KernelTestCase
{
    /** @var Client */
    private $client;

    /** @var IdsResolver */
    private $resolver;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->client = $kernel->getContainer()->get('neo4j.client.default');
        $this->resolver = $kernel->getContainer()->get('app.mutual_friends_resolver.neo4j.ids_resolver');

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
