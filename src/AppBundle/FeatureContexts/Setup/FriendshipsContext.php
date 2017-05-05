<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\CommandBus\CommandBusInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Friendships\Commands\StoreFriendshipCommand;
use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use SocNet\Users\User;
use Behat\Behat\Context\Context;

class FriendshipsContext implements Context
{
    private $storage;
    private $uuidGenerator;
    private $commandBus;
    private $friendshipRequests;

    public function __construct(StorageInterface $storage, UuidGeneratorInterface $uuidGenerator, CommandBusInterface $commandBus, FriendshipRequestsRepositoryInterface $friendshipRequests)
    {
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
        $this->friendshipRequests = $friendshipRequests;
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
        $friend = $this->storage->get('created_user_' . $name);

        $this->storeFriendshipBetweenUsers($current, $friend);
    }

    /**
     * @Given :first is friend with :second
     */
    public function somebodyIsFriendWithSomebody(string $first, string $second) : void
    {
        if (in_array($first, ['she', 'he'])) {
            $firstUser = $this->storage->get('last_created_user');
        } else {
            $firstUser = $this->storage->get('created_user_' . $first);
        }

        $secondUser = $this->storage->get('created_user_' . $second);

        $this->storeFriendshipBetweenUsers($firstUser, $secondUser);
    }

    private function storeFriendshipBetweenUsers(User $first, User $second) : void
    {
        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $first, $second));

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $second, $first));
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
        $this->commandBus->handle(new SendFriendshipRequestCommand($id, $friend, $user));

        $request = $this->friendshipRequests->find($id);

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
        $this->commandBus->handle(new SendFriendshipRequestCommand($id, $user, $friend));

        $request = $this->friendshipRequests->find($id);

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
