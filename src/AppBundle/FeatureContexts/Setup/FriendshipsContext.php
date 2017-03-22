<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\Friendship;
use AppBundle\Entity\FriendshipRequest;
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
     * @Given we (also) are friends
     */
    public function weAreFriends() : void
    {
        $friend = $this->storage->get('last_created_user');
        $current = $this->storage->get('logged_user');

        $this->storeFriendshipBetweenUsers($friend, $current);
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

        if (in_array($first, ['she', 'he'])) {
            $firstUser = $this->storage->get('last_created_user');
        } else {
            $firstUser = $this->storage->get('created_user_' . $first);
        }

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

    /**
     * @Given we are not friends
     */
    public function weAreNotFriends() : void
    {
        // nothing to be done here, at least for now
    }

    /**
     * @Given :name wants us to be friends
     */
    public function somebodyWantsUsToBeFriends(string $name) : void
    {
        $friend = $this->storage->get('created_user_' . $name);
        $user = $this->storage->get('logged_user');

        $request = new FriendshipRequest();
        $request->setFromUser($friend);
        $request->setToUser($user);

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        $this->storage->set('current_friendship_request', $request);
    }

    /**
     * @Given I have sent a friendship request to :name
     */
    public function iHaveSentAFriendshipRequestTo(string $name) : void
    {
        $friend = $this->storage->get('created_user_' . $name);
        $user = $this->storage->get('logged_user');

        $request = new FriendshipRequest();
        $request->setFromUser($user);
        $request->setToUser($friend);

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        $this->storage->set('current_friendship_request', $request);
    }

    /**
     * @Given (s)he hasn't responded yet
     */
    public function sheHasnTRespondedYet()
    {
        // nothing to be done
    }
}
