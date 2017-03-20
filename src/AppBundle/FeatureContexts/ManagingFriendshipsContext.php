<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;

class ManagingFriendshipsContext implements Context
{
    private $session;
    private $router;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @When I want to manage my friendships
     */
    public function iWantToManageMyFriendships() : void
    {
        $url = $this->router->generate('app.friends.index');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see that I have :count friends
     */
    public function iShouldSeeThatIHaveFriends(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#friends-list .panel');

        if (count($list) !== $count) {
            throw new \Exception(sprintf(
                'Expected to find %d friends, found %d.',
                $count,
                count($list)
            ));
        }
    }

    /**
     * @Then I should see that I am friend with :first and :second
     */
    public function iShouldSeeThatIAmFriendWith(string ...$names) : void
    {
        $list = $this->session->getPage()->findAll('css', '#friends-list .user-name');
        $foundNames = [];

        foreach ($list as $friend) {
            /** @var NodeElement $friend */
            $foundNames[] = $friend->getText();
        }

        sort($names);
        sort($foundNames);

        if ($names !== $foundNames) {
            throw new \Exception(sprintf(
                'Expected friends: %s. Found friends: %s.',
                implode(', ', $names),
                implode(', ', $foundNames)
            ));
        }
    }

    /**
     * @Then I should see that I have :count friendship request(s)
     */
    public function iShouldSeeThatIHaveFriendRequest(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#received-friendship-requests-list .panel');

        if (count($list) !== $count) {
            throw new \Exception(
                'Expected to find %d received friendship requests, found %d instead.',
                $count,
                count($list)
            );
        }
    }

    /**
     * @Then it should be from :name
     */
    public function itShouldBeFrom(string $name) : void
    {
        $listed = $this->session->getPage()->find('css', '#received-friendship-requests-list .user-name')->getText();

        if ($name !== $listed) {
            throw new \Exception(sprintf(
                'Could not find a friendship request from %s.',
                $name
            ));
        }
    }

    /**
     * @Then I should see that I have :count sent friendship request(s)
     */
    public function iShouldSeeThatIHaveSentFriendshipRequest(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#sent-friendship-requests-list .panel');

        if (count($list) !== $count) {
            throw new \Exception(
                'Expected to find %d sent friendship requests, found %d instead.',
                $count,
                count($list)
            );
        }
    }

    /**
     * @Then it should be for :name
     */
    public function itShouldBeFor(string $name) : void
    {
        $listed = $this->session->getPage()->find('css', '#sent-friendship-requests-list .user-name')->getText();

        if ($name !== $listed) {
            throw new \Exception(sprintf(
                'Could not find a friendship request sent to %s.',
                $name
            ));
        }
    }
}
