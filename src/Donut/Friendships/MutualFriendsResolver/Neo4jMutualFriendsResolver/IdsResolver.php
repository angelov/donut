<?php

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
