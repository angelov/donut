<?php

namespace SocNet\Friendships\FriendshipsRecorder;

use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\Friendship;
use SocNet\Users\User;

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
                (first)<-[:FRIEND]-(second)
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
