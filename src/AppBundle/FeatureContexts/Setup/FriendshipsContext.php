<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Users\User;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use GraphAware\Neo4j\Client\Client;

class FriendshipsContext implements Context
{
    private $storage;
    private $entityManager;
    private $neo4j;
    private $uuidGenerator;

    public function __construct(StorageInterface $storage, EntityManager $entityManager, Client $neo4j, UuidGeneratorInterface $uuidGenerator)
    {
        $this->storage = $storage;
        $this->entityManager = $entityManager; // @todo use a repository instead
        $this->neo4j = $neo4j;
        $this->uuidGenerator = $uuidGenerator;
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
        $id = $this->uuidGenerator->generate();
        $friendship = new Friendship($id, $first, $second);
        $this->entityManager->persist($friendship);

        $id = $this->uuidGenerator->generate();
        $friendship = new Friendship($id, $second, $first);
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
     * @Given I am not friend with :name
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

        $id = $this->uuidGenerator->generate();
        $request = new FriendshipRequest($id, $friend, $user);

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

        $id = $this->uuidGenerator->generate();
        $request = new FriendshipRequest($id, $user, $friend);

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        $this->storage->set('current_friendship_request', $request);
    }

    /**
     * @Given (s)he hasn't responded yet
     */
    public function sheHasnTRespondedYet() : void
    {
        // nothing to be done
    }
}
