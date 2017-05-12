<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use SocNet\Behat\Pages\Elements\UserCard;
use SocNet\Behat\Pages\Users\BrowsingUsersPage;
use SocNet\Behat\Service\Storage\StorageInterface;
use Webmozart\Assert\Assert;

class BrowsingUsersContext implements Context
{
    private $storage;
    private $browsingUsersPage;

    public function __construct(StorageInterface $storage, BrowsingUsersPage $browsingUsersPage)
    {
        $this->storage = $storage;
        $this->browsingUsersPage = $browsingUsersPage;
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

    private function getCurrentUserCard(): UserCard
    {
        $name = $this->storage->get('current_user_name');

        return $this->browsingUsersPage->getUserCard($name);
    }
}
