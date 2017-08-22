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

use Angelov\Donut\Behat\Pages\Users\UserProfilePage;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Users\User;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ViewingUserProfileContext implements Context
{
    private $storage;
    private $userProfilePage;

    public function __construct(StorageInterface $storage, UserProfilePage $userProfilePage)
    {
        $this->storage = $storage;
        $this->userProfilePage = $userProfilePage;
    }

    /**
     * @When I want to view :name's profile
     * @When I'm viewing :name's profile
     */
    public function iWantToViewUserSProfile(string $name) : void
    {
        /** @var User $user */
        $user = $this->storage->get('created_user_' . $name);

        $this->userProfilePage->open(['id' => $user->getId()]);
    }

    /**
     * @Then I should see that (s)he has :count friend(s)
     */
    public function iShouldSeeThatHeHasFriends(int $count) : void
    {
        $found = $this->userProfilePage->countFriends();

        Assert::eq($count, $found, 'Expected to find %d friends, but found %d');
    }

    /**
     * @Given I should be on the list of friends
     */
    public function iShouldBeOnTheListOfFriends() : void
    {
        $myName = $this->storage->get('logged_user')->getName();

        $this->checkIfUserIsInFriendsList($myName);
    }

    /**
     * @Given :name should also be on the list
     */
    public function userShouldAlsoBeOnTheList(string $name) : void
    {
        $this->checkIfUserIsInFriendsList($name);
    }

    private function checkIfUserIsInFriendsList(string $name) : void
    {
        Assert::true(
            $this->userProfilePage->friendsListContainsUser($name),
            'Could not find %s in the friends list.'
        );
    }

    /**
     * @Then I should see that we have :count mutual friend(s)
     */
    public function iShouldSeeThatWeHaveMutualFriends(int $count) : void
    {
        $found = $this->userProfilePage->countMutualFriends();

        Assert::same($count, $found, 'Expected %s mutual friends, found %s.');
    }

    /**
     * @Then that friend should be :name
     */
    public function thatFriendShouldBe(string $name) : void
    {
        Assert::true(
            $this->userProfilePage->mutualFriendsListContainsUser($name)
        );
    }

    /**
     * @Then I should see that (s)he has shared :count thoughts
     */
    public function iShouldSeeThatSheHasSharedThoughts(int $count) : void
    {
        Assert::eq(
            $count, $this->userProfilePage->countThoughts(),
            'Expected to find %d thoughts, found %d instead.'
        );
    }

    /**
     * @Then I should see a message that (s)he has no friends
     */
    public function iShouldSeeAMessageThatHeHasNoFriends() : void
    {
        Assert::true($this->userProfilePage->hasNoFriendsMessage());
    }

    /**
     * @Then I should see a message that we don't have any mutual friends
     */
    public function iShouldSeeAMessageThatWeDonTHaveAnyMutualFriends() : void
    {
        Assert::true($this->userProfilePage->hasNoMutualFriendsMessage());
    }

    /**
     * @Then I should see a message that (s)he hasn't shared anything yet
     */
    public function iShouldSeeAMessageThatSheHasnTSharedAnythingYet() : void
    {
        Assert::true($this->userProfilePage->hasNoSharedThoughtsMessage());
    }
}
