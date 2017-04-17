<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class BrowsingUsersContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, Storage $storage)
    {
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When I want to browse the users
     * @Given I am browsing the users
     */
    public function iWantToBrowseTheUsers() : void
    {
        $url = $this->router->generate('app.users.index');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see :count users in the list
     */
    public function iShouldSeeUsersInTheList(int $count) : void
    {
        $found = $this->session->getPage()->findAll('css', '#users-list .user-card');

        Assert::same($count, count($found), 'Expected to find %s listed users, found %s');
    }

    /**
     * @Then those users should be :first, :second, :third and :fourth
     */
    public function thoseUsersShouldBe(string ...$names) : void
    {
        $found = $this->session->getPage()->findAll('css', '#users-list .user-card .user-name');

        $found = array_map(function(NodeElement $element) : string {
            return $element->getText();
        }, $found);

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
        $this->checkIfUserHasBadge($name, sprintf('%d thoughts', $count));
        $this->storage->set('current_user_name', $name);
    }

    /**
     * @Given I should see that his email is :email
     */
    public function iShouldSeeThatHisEmailIs(string $email) : void
    {
        $name = $this->storage->get('current_user_name');
        $this->checkIfUserHasBadge($name, $email);
    }

    /**
     * @Then I should see that (s)he has :count friend(s)
     */
    public function iShouldSeeThatHeHasFriends(int $count) : void
    {
        $name = $this->storage->get('current_user_name');
        $this->checkIfUserHasBadge($name, sprintf('%d friends', $count));
    }

    /**
     * @Then I should see that we have :count mutual friend(s)
     */
    public function iShouldSeeThatWeHaveMutualFriends(int $count) : void
    {
        $name = $this->storage->get('current_user_name');
        $this->checkIfUserHasBadge($name, sprintf('%d mutual friends', $count));
    }

    private function checkIfUserHasBadge(string $name, string $badgeContent) : void
    {
        $card = $this->session->getPage()->find('css', sprintf('#users-list .user-card:contains("%s")', $name));

        Assert::true($card->has('css', sprintf('.badge:contains("%s")', $badgeContent)));
    }

    /**
     * @Then that friend should be :name
     */
    public function thatFriendShouldBe(string $name) : void
    {
        $currentUserName = $this->storage->get('current_user_name');

        $card = $this->session->getPage()->find('css', sprintf('#users-list .user-card:contains("%s")', $currentUserName));
        $mutualFriend = $card->find('css', '.mutual-friends-list .mutual-friend-name')->getText();

        Assert::same($mutualFriend, $name);
    }
}
