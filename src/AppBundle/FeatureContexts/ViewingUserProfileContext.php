<?php

namespace AppBundle\FeatureContexts;

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class ViewingUserProfileContext implements Context
{
    private $session;
    private $em;
    private $router;
    private $storage;

    public function __construct(Session $session, EntityManager $em, RouterInterface $router, Storage $storage)
    {
        $this->em = $em;
        $this->router = $router;
        $this->session = $session;
        $this->storage = $storage;
    }

    /**
     * @When I want to view :name's profile
     * @When I'm viewing :name's profile
     */
    public function iWantToViewUserSProfile(string $name) : void
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['name' => $name]);
        $url = $this->router->generate('app.users.show', ['id' => $user->getId()]);

        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see that (s)he has :count friend(s)
     */
    public function iShouldSeeThatHeHasFriends(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#friends-list li a');
        $existing = count($list);

        Assert::same($count, $existing, 'Expected to find %d friends, but found %d');
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
            $this->session->getPage()->has('css', sprintf('#friends-list li a:contains("%s")', $name)),
            'Could not find %s in the friends list.'
        );
    }

    /**
     * @Then I should see that we have :count mutual friend(s)
     */
    public function iShouldSeeThatWeHaveMutualFriends(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#mutual-friends-list li a');

        Assert::same($count, count($list), 'Expected %s mutual friends, found %s.');
    }

    /**
     * @Then that friend should be :name
     */
    public function thatFriendShouldBe(string $name) : void
    {
        $mutualFriend = $this->session->getPage()->find('css', '#mutual-friends-list li a');

        Assert::same($name, $mutualFriend->getText());
    }

    /**
     * @Then I should see that (s)he has shared :count thoughts
     */
    public function iShouldSeeThatSheHasSharedThoughts(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#thoughts-list pre');

        Assert::same($count, count($list), 'Expected to find %d thoughts, found %d instead.');
    }

    /**
     * @Then I should see a message that (s)he has no friends
     */
    public function iShouldSeeAMessageThatHeHasNoFriends() : void
    {
        Assert::true($this->session->getPage()->has('css', '#friends-list li:contains("The user has no friends.")'));
    }

    /**
     * @Then I should see a message that we don't have any mutual friends
     */
    public function iShouldSeeAMessageThatWeDonTHaveAnyMutualFriends() : void
    {
        Assert::true($this->session->getPage()->has('css', '#mutual-friends-list li:contains("You don\'t have any mutual friends.")'));
    }

    /**
     * @Then I should see a message that (s)he hasn't shared anything yet
     */
    public function iShouldSeeAMessageThatSheHasnTSharedAnythingYet() : void
    {
        Assert::true($this->session->getPage()->has('css', '#thoughts-list p:contains("The user hasn\'t shared any thoughts yet.")'));
    }
}
