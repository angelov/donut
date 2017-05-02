<?php

namespace AppBundle\FeatureContexts;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class ManagingFriendshipsContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, StorageInterface $storage)
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

        Assert::same($count, count($list), 'Expected to find %s friends, found %s.');
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

        $names = implode(', ', $names);
        $foundNames = implode(', ', $foundNames);

        Assert::same($names, $foundNames, 'Expected friends: %s. Found friends: %s.');
    }

    /**
     * @Then I should see that I have :count friendship request(s)
     */
    public function iShouldSeeThatIHaveFriendRequest(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#received-friendship-requests-list .panel');

        Assert::same($count, count($list), 'Expected to find %s received friendship requests, found %s instead.');
    }

    /**
     * @Then it should be from :name
     */
    public function itShouldBeFrom(string $name) : void
    {
        $listed = $this->session->getPage()->find('css', '#received-friendship-requests-list .user-name')->getText();

        Assert::same($name, $listed, 'Could not find a friendship request from %s.');
    }

    /**
     * @Then I should see that I have :count sent friendship request(s)
     */
    public function iShouldSeeThatIHaveSentFriendshipRequest(int $count) : void
    {
        $list = $this->session->getPage()->findAll('css', '#sent-friendship-requests-list .panel');

        Assert::same($count, count($list), 'Expected to find %s sent friendship requests, found %s instead.');
    }

    /**
     * @Then it should be for :name
     */
    public function itShouldBeFor(string $name) : void
    {
        $listed = $this->session->getPage()->find('css', '#sent-friendship-requests-list .user-name')->getText();

        Assert::same($name, $listed, 'Could not find a friendship request sent to %s.');
    }

    /**
     * @Then I should see that I haven't got any received friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnyReceivedFriendshipRequests() : void
    {
        Assert::true($this->session->getPage()->hasContent('0 non-responded friendship requests found.'));
    }

    /**
     * @Then I should see that I haven't got any sent friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnySentFriendshipRequests() : void
    {
        Assert::true($this->session->getPage()->hasContent('You haven\'t sent any friendship requests.'));
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
        Assert::true($this->session->getPage()->hasContent('Friendship request successfully accepted!'));
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
        /** @var \SocNet\Friendships\FriendshipRequests\FriendshipRequest $request */
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
        Assert::true($this->session->getPage()->hasContent('Friendship request successfully declined!'));
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
        Assert::true($this->session->getPage()->hasContent('Sorry to see broken friendships.'));
    }

    /**
     * @Then I shouldn't see :name in the list of friends anymore
     */
    public function iShouldnTSeeInTheListOfFriendsAnymore(string $name) : void
    {
        Assert::false($this->session->getPage()->has('css', sprintf('#friends-list .user-card .user-name:contains("%s")', $name)));
    }

    /**
     * @When I want to cancel the friendship request
     */
    public function iWantToCancelTheFriendshipRequest() : void
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
    public function iShouldBeNotifiedThatTheRequestIsCancelled() : void
    {
        Assert::true($this->session->getPage()->hasContent('Friendship request successfully cancelled!'));
    }

    /**
     * @Then I should be notified that a friendship request is sent
     */
    public function iShouldBeNotifiedThatAFriendshipRequestIsSent() : void
    {
        Assert::true($this->session->getPage()->hasContent('Friendship request successfully sent!'));
    }

    /**
     * @Then I should see a message that I still don't have any friends
     */
    public function iShouldSeeAMessageThatIStillDonTHaveAnyFriends() : void
    {
        Assert::true($this->session->getPage()->has('css', '#friends-list li:contains("You still don\'t have any friends :(")'));
    }

    /**
     * @When I want to be friends with :name
     */
    public function iWantToBeFriendsWith(string $name) : void
    {
        $card = $this->session->getPage()->find('css', sprintf('#users-list .user-card:contains("%s")', $name));
        $button = $card->find('css', '.btn:contains("Add friend")');

        $button->press();
    }

    /**
     * @Then I should see suggestion to add :name as a friend
     */
    public function iShouldSeeSuggestionToAddAsAFriend(string $name) : void
    {
        Assert::true($this->session->getPage()->has('css', sprintf('#friends-suggestions .user-card .user-name:contains("%s")', $name)));
    }

    /**
     * @Then I should not see suggestion to add :name as a friend
     */
    public function iShouldNotSeeSuggestionToAddAsAFriend(string $name) : void
    {
        Assert::false($this->session->getPage()->has('css', sprintf('#friends-suggestions .user-card .user-name:contains("%s")', $name)));
    }

    /**
     * @Then I should see a message that there are no friends suggested for me
     */
    public function iShouldSeeAMessageThatThereAreNoFriendsSuggestedForMe() : void
    {
        Assert::true($this->session->getPage()->has('css', '#friends-suggestions li:contains("No suggested friends for you. Sorry.")'));
    }

    /**
     * @When I choose to ignore the suggestion to add :name as a friend
     */
    public function iChooseToIgnoreTheSuggestionToAddAsAFriend(string $name) : void
    {
        $button = $this->session->getPage()->find('css', sprintf('#friends-suggestions .user-card:contains("%s") .btn-ignore-friendship-suggestion', $name));
        $button->press();
    }
}
