<?php

namespace SocNet\Friendships\MutualFriendsResolver;

use GraphAware\Neo4j\Client\Client;
use SocNet\Friendships\MutualFriendsResolver\UsersProvider\UsersProviderInterface;
use SocNet\Users\User;

class Neo4jMutualFriendsResolver implements MutualFriendsResolverInterface
{
    private $client;
    private $usersProvider;

    public function __construct(Client $client, UsersProviderInterface $usersProvider)
    {
        $this->client = $client;
        $this->usersProvider = $usersProvider;
    }

    /**
     * @return User[]
     * @todo refactor
     */
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
                y.id
       
        ';

        $result = $this->client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);

        if (count($result->records()) === 0) {
            return [];
        }

        $users = [];

        foreach ($result->records() as $record) {
            $id = $record->get('y.id');
            $users[] = $this->usersProvider->getById($id);
        }

        return $users;
    }

}
