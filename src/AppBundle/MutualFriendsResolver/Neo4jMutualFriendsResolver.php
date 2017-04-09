<?php

namespace AppBundle\MutualFriendsResolver;

use SocNet\Users\User;
use AppBundle\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use GraphAware\Bolt\Result\Type\Node;
use GraphAware\Neo4j\Client\Client;

class Neo4jMutualFriendsResolver implements MutualFriendsResolverInterface
{
    private $client;
    private $users;

    public function __construct(Client $client, UsersProviderInterface $users)
    {
        $this->client = $client;
        $this->users = $users;
    }

    public function forUsers(User $first, User $second): array
    {
        if ($first->equals($second)) {
            return [];
        }

        $query = '
            
            MATCH
                (first:User {id: {first}}),
                (second:User {id: {second}}),
                (y:User),
                (first)-[:FRIEND]->(y),
                (y)<-[:FRIEND]-(second)
            RETURN
                y
       
        ';

        $result = $this->client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);

        $ids = [];

        foreach ($result->records() as $record) {
            /** @var Node $value */
            foreach ($record->values() as $value) {
                $ids[] = $value->get('id');
            }
        }

        $ids = array_unique($ids);

        $users = [];

        foreach ($ids as $id) {
            $users[] = $this->users->getById($id);
        }

        return $users;
    }
}