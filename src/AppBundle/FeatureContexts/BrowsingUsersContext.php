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

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Angelov\Donut\Behat\Pages\Users\UserCard;
use Angelov\Donut\Behat\Pages\Users\BrowsingUsersPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Webmozart\Assert\Assert;

class BrowsingUsersContext implements Context
{
    private $storage;
    private $browsingUsersPage;
    private $alertsChecker;

    public function __construct(StorageInterface $storage, BrowsingUsersPage $browsingUsersPage, AlertsCheckerInterface $alertsChecker)
    {
        $this->storage = $storage;
        $this->browsingUsersPage = $browsingUsersPage;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When I want to browse the users
     * @Given I am browsing the users
     */
    public function iWantToBrowseTheUsers() : void
    {
        $this->browsingUsersPage->open();
    }

    /**
     * @Then I should see :count users in the list
     */
    public function iShouldSeeUsersInTheList(int $count) : void
    {
        $found = $this->browsingUsersPage->countDisplayedUsers();

        Assert::same($count, $found, 'Expected to find %s listed users, found %s');
    }

    /**
     * @Then those users should be :first, :second, :third and :fourth
     */
    public function thoseUsersShouldBe(string ...$names) : void
    {
        $found = $this->browsingUsersPage->getDisplayedUserNames();

        sort($names);
        sort($found);

        $names = implode(', ', $names);
        $found = implode(', ', $found);

        Assert::same($names, $found, 'Expected users: %s. Found: %s');
    }

    /**
     * @Then I should see that :name has shared :count thoughts
     */
    public function iShouldSeeThatUserHasSharedThoughts(string $name, int $count) : void
    {
        $found = $this->browsingUsersPage->getUserCard($name)->getNumberOfThoughts();

        Assert::eq($count, $found, 'Expected number of thoughts for user to be %s, but it is %s');

        $this->storage->set('current_user_name', $name);
    }

    /**
     * @Given I should see that his email is :email
     */
    public function iShouldSeeThatHisEmailIs(string $email) : void
    {
        $name = $this->storage->get('current_user_name');

        $found = $this->getCurrentUserCard()->getEmail();

        Assert::same($email, $found, 'Expected displayed e-mail for user to be %s, but it is %s');
    }

    /**
     * @Then I should see that (s)he has :count friend(s)
     */
    public function iShouldSeeThatHeHasFriends(int $count) : void
    {
        $found = $this->getCurrentUserCard()->getNumberOfFriends();

        Assert::eq($count, $found, 'Expected number of user\'s friends to be %s, but it is %s');
    }

    /**
     * @Then I should see that we have :count mutual friend(s)
     */
    public function iShouldSeeThatWeHaveMutualFriends(int $count) : void
    {
        $found = $this->getCurrentUserCard()->getNumberOfMutualFriends();

        Assert::eq($count, $found, 'Expected number of mutual friends to be %s, but it is %s');
    }

    /**
     * @Then that friend should be :name
     */
    public function thatFriendShouldBe(string $name) : void
    {
        $names = $this->getCurrentUserCard()->getMutualFriendsNames();
        $mutualFriend = $names[0];

        Assert::same($mutualFriend, $name);
    }

    /**
     * @When I want to be friends with :name
     */
    public function iWantToBeFriendsWith(string $name) : void
    {
        $this->browsingUsersPage->getUserCard($name)->addAsFriend();
    }

    /**
     * @Then I should be notified that a friendship request is sent
     */
    public function iShouldBeNotifiedThatAFriendshipRequestIsSent() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Friendship request successfully sent!', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    private function getCurrentUserCard(): UserCard
    {
        $name = $this->storage->get('current_user_name');

        return $this->browsingUsersPage->getUserCard($name);
    }
}
