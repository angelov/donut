<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\Friendship;
use AppBundle\Entity\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use GraphAware\Neo4j\Client\Client;

class FriendshipsContext implements Context
{
    private $storage;
    private $entityManager;
    private $neo4j;

    public function __construct(Storage $storage, EntityManager $entityManager, Client $neo4j)
    {
        $this->storage = $storage;
        $this->entityManager = $entityManager; // @todo use a repository instead
        $this->neo4j = $neo4j;
    }

    /**
     * @Given I am friend with :name
     */
    public function iAmFriendWith(string $name) : void
    {
        $current = $this->storage->get('logged_user');
        $friend = $this->entityManager->getRepository(User::class)->findOneBy(['name' => $name]);

        $this->storeFriendshipBetweenUsers($current, $friend);
    }

    /**
     * @Given :first is friend with :second
     */
    public function somebodyIsFriendWithSomebody(string $first, string $second) : void
    {
        $repo = $this->entityManager->getRepository(User::class);

        $firstUser = $repo->findOneBy(['name' => $first]);
        $secondUser = $repo->findOneBy(['name' => $second]);

        $this->storeFriendshipBetweenUsers($firstUser, $secondUser);
    }

    private function storeFriendshipBetweenUsers(User $first, User $second) : void
    {
        $friendship = Friendship::createBetween($first, $second);
        $this->entityManager->persist($friendship);

        $friendship = Friendship::createBetween($second, $first);
        $this->entityManager->persist($friendship);

        $this->entityManager->flush();

        $client = $this->neo4j;

        $query = 'MERGE (n:User {id: {id}, name: {name}})';
        $client->run($query, ['id' => $first->getId(), 'name' => $first->getName()]);
        $client->run($query, ['id' => $second->getId(), 'name' => $second->getName()]);

        $query = '
            MATCH
                (first:User {id:{first}}),
                (second:User {id:{second}})
            CREATE
                (first)<-[:FRIEND]-(second), (first)-[:FRIEND]->(second)
        ';

        $client->run($query, [
            'first' => $first->getId(),
            'second' => $second->getId()
        ]);
    }
}
