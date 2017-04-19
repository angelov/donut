<?php

namespace SocNet\Tests\Friendships\FriendshipsRecorder;

use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipsRecorder\Neo4jFriendshipsRecorder;
use SocNet\Users\Repositories\UsersRepositoryInterface;
use SocNet\Users\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Neo4jFriendshipsRecorderTest extends KernelTestCase
{
    /** @var Neo4jFriendshipsRecorder */
    private $recorder;

    /** @var Client */
    private $client;

    /** @var UsersRepositoryInterface */
    private $users;

    public function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $this->recorder = $kernel->getContainer()->get('app.friendships.friendships_recorder.neo4j');
        $this->client = $kernel->getContainer()->get('neo4j.client.default');
        $this->users = $kernel->getContainer()->get('app.users.repository.default');
    }

    /** @test */
    public function it_stores_friendships_in_neo4j()
    {
        $sender = new User('John', 'john@example.com', '123456');
        $this->users->store($sender);

        $recipient = new User('James', 'james@example.com', '123456');
        $this->users->store($recipient);

        $friendship = new Friendship($sender, $recipient);

        $this->recorder->recordCreated($friendship);

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

        $this->assertCount(2, $records);
    }

    public function it_removed_friendships_from_neo4j()
    {
        $sender = new User('John', 'john@example.com', '123456');
        $this->users->store($sender);

        $recipient = new User('James', 'james@example.com', '123456');
        $this->users->store($recipient);

        $friendship = new Friendship($sender, $recipient);

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
