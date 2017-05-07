<?php

namespace SocNet\Tests\Friendships\FriendshipsRecorder;

use AppBundle\Factories\FriendshipsFactory;
use AppBundle\Factories\UsersFactory;
use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipsRecorder\Neo4jFriendshipsRecorder;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Neo4jFriendshipsRecorderTest extends KernelTestCase
{
    /** @var Neo4jFriendshipsRecorder */
    private $recorder;

    /** @var Client */
    private $client;

    /** @var FriendshipsFactory */
    private $friendshipsFactory;

    /** @var $usersFactory UsersFactory */
    private $usersFactory;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->recorder = $kernel->getContainer()->get('app.friendships.friendships_recorder.neo4j');
        $this->client = $kernel->getContainer()->get('neo4j.client.default');
        $this->friendshipsFactory = $kernel->getContainer()->get('app.factories.friendships');
        $this->usersFactory = $kernel->getContainer()->get('app.factories.users.faker');
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
