<?php

namespace AppBundle\FeatureContexts;

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

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

        if ($existing !== $count) {
            throw new \Exception(sprintf(
                'Expected to find %d friends, but found %d',
                $count,
                $existing
            ));
        }

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
        $list = $this->session->getPage()->findAll('css', '#friends-list li a');

        /** @var NodeElement $current */
        foreach ($list as $current) {
            if ($name === $current->getText()) {
                return;
            }
        }

        throw new \Exception(sprintf(
            'Could not find %s in the friends list.',
            $name
        ));
    }

    /**
     * @Then I should see that we have :count mutual friend(s)
     */
    public function iShouldSeeThatWeHaveMutualFriends(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#mutual-friends-list li a');

        if (count($list) !== $count) {
            throw new \Exception(sprintf(
                'Expected %d mutual friends, found %d.',
                $count,
                count($list)
            ));
        }
    }

    /**
     * @Given that friend should be :name
     */
    public function thatFriendShouldBe(string $name) : void
    {
        $mutualFriend = $this->session->getPage()->find('css', '#mutual-friends-list li a');

        if ($mutualFriend->getText() !== $name) {
            throw new \Exception();
        }
    }
}
