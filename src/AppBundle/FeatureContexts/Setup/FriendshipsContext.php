<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\FeatureContexts\Setup;

use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Friendships\Commands\StoreFriendshipCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use Angelov\Donut\Friendships\FriendshipRequests\Repositories\FriendshipRequestsRepositoryInterface;
use Angelov\Donut\Users\User;
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
        $this->commandBus->handle(new StoreFriendshipCommand($id, $first->getId(), $second->getId()));

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new StoreFriendshipCommand($id, $second->getId(), $first->getId()));
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
        /** @var User $friend */
        $friend = $this->storage->get('created_user_' . $name);

        /** @var User $user */
        $user = $this->storage->get('logged_user');

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new SendFriendshipRequestCommand($id, $friend->getId(), $user->getId()));

        $request = $this->friendshipRequests->find($id);

        $this->storage->set('current_friendship_request', $request);
    }

    /**
     * @Given I have sent a friendship request to :name
     */
    public function iHaveSentAFriendshipRequestTo(string $name) : void
    {
        /** @var User $friend */
        $friend = $this->storage->get('created_user_' . $name);

        /** @var User $user */
        $user = $this->storage->get('logged_user');

        $id = $this->uuidGenerator->generate();
        $this->commandBus->handle(new SendFriendshipRequestCommand($id, $user->getId(), $friend->getId()));

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
