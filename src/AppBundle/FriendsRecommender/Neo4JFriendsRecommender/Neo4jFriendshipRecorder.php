<?php

namespace AppBundle\FriendsRecommender\Neo4JFriendsRecommender;

use AppBundle\Entity\User;
use AppBundle\FriendsRecommender\FriendshipRecorderInterface;
use GraphAware\Neo4j\Client\Client;

class Neo4jFriendshipRecorder implements FriendshipRecorderInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function record(User $first, User $second)
    {
        $query = 'MERGE (n:User {id: {id}, name: {name}})';
        $this->client->run($query, ['id' => $first->getId(), 'name' => $first->getName()]);
        $this->client->run($query, ['id' => $second->getId(), 'name' => $second->getName()]);

        $query = '
            MATCH
                (first:User {id:{first}}),
                (second:User {id:{second}})
            CREATE
                (first)<-[:FRIEND]-(second), (first)-[:FRIEND]->(second)
        ';

        $this->client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);
    }
}
