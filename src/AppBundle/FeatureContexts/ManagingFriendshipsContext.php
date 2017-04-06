<?php

namespace AppBundle\FeatureContexts;

use AppBundle\Entity\FriendshipRequest;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;

class ManagingFriendshipsContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, Storage $storage)
    {
        $this->router = $router;
        $this->session = $session;
        $this->storage = $storage;
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

    /**
     * @Then I should see that I haven't got any received friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnyReceivedFriendshipRequests() : void
    {
        $found = $this->session->getPage()->hasContent('0 non-responded friendship requests found.');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should see that I haven't got any sent friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnySentFriendshipRequests() : void
    {
        $found = $this->session->getPage()->hasContent('You haven\'t sent any friendship requests.');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @When I accept her friendship request
     * @When I accept his friendship request
     */
    public function iAcceptHerFriendshipRequest() : void
    {
        /** @var FriendshipRequest $request */
        $request = $this->storage->get('current_friendship_request');
        $toFind = $request->getFromUser()->getName();

        $button = $this->session->getPage()->find('css', sprintf('#received-friendship-requests-list .user-card:contains("%s") .btn-accept-friendship', $toFind));
        $button->press();
    }

    /**
     * @Then I should be notified that we've became friends
     */
    public function iShouldBeNotifiedThatWeVeBecameFriends() : void
    {
        $found = $this->session->getPage()->hasContent('Friendship request successfully accepted!');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Given I shouldn't see the request anymore
     */
    public function iShouldnTSeeTheRequestAnymore() : void
    {
        $this->iShouldSeeThatIHaveFriendRequest(0);
    }

    /**
     * @When I decline her friendship request
     * @When I decline his friendship request
     */
    public function iDeclineHerFriendshipRequest() : void
    {
        /** @var FriendshipRequest $request */
        $request = $this->storage->get('current_friendship_request');
        $toFind = $request->getFromUser()->getName();

        $button = $this->session->getPage()->find('css', sprintf('#received-friendship-requests-list .user-card:contains("%s") .btn-decline-friendship', $toFind));
        $button->press();
    }

    /**
     * @Then I should be notified that the request is removed
     */
    public function iShouldBeNotifiedThatTheRequestIsRemoved() : void
    {
        $found = $this->session->getPage()->hasContent('Friendship request successfully declined!');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Given I delete my friendship with :name
     * @Given I want to stop being a friend with :name
     */
    public function iDeleteMyFriendshipWith(string $name) : void
    {
        $friend = $this->storage->get('created_user_' . $name);
        $toFind = $friend->getName();

        $button = $this->session->getPage()->find('css', sprintf('#friends-list .user-card:contains("%s") .btn-delete-friendship', $toFind));
        $button->press();
    }

    /**
     * @Then I should be notified that the friendship is deleted
     */
    public function iShouldBeNotifiedThatTheFriendshipIsDeleted() : void
    {
        $found = $this->session->getPage()->hasContent('Sorry to see broken friendships.');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I shouldn't see :name in the list of friends anymore
     */
    public function iShouldnTSeeInTheListOfFriendsAnymore(string $name) : void
    {
        $found = $this->session->getPage()->findAll('css', sprintf('#friends-list .user-card .user-name:contains("%s")', $name));

        if ($found) {
            throw new \Exception();
        }
    }

    /**
     * @When I want to cancel the friendship request
     */
    public function iWantToCancelTheFriendshipRequest()
    {
        /** @var FriendshipRequest $request */
        $request = $this->storage->get('current_friendship_request');
        $toFind = $request->getToUser()->getName();

        $button = $this->session->getPage()->find('css', sprintf('#sent-friendship-requests-list .user-card:contains("%s") .btn-cancel-friendship-request', $toFind));
        $button->press();
    }

    /**
     * @Then I should be notified that the request is cancelled
     */
    public function iShouldBeNotifiedThatTheRequestIsCancelled()
    {
        $found = $this->session->getPage()->hasContent('Friendship request successfully cancelled!');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should see a message that I still don't have any friends
     */
    public function iShouldSeeAMessageThatIStillDonTHaveAnyFriends()
    {
        $found = $this->session->getPage()->find('css', '#friends-list li:contains("You still don\'t have any friends :(")');

        if (!$found) {
            throw new \Exception();
        }
    }
}
