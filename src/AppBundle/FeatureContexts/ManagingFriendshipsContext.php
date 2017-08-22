<?php

namespace AppBundle\FeatureContexts;

use Angelov\Donut\Behat\Pages\Friendships\FriendshipsManagementPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Friendships\FriendshipRequests\FriendshipRequest;
use Behat\Behat\Context\Context;
use Angelov\Donut\Users\User;
use Webmozart\Assert\Assert;

class ManagingFriendshipsContext implements Context
{
    private $storage;
    private $friendshipsManagementPage;
    private $alertsChecker;

    public function __construct(
        StorageInterface $storage,
        FriendshipsManagementPage $friendshipsManagementPage,
        AlertsCheckerInterface $alertsChecker
    ) {
        $this->storage = $storage;
        $this->friendshipsManagementPage = $friendshipsManagementPage;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When I want to manage my friendships
     */
    public function iWantToManageMyFriendships() : void
    {
        $this->friendshipsManagementPage->open();
    }

    /**
     * @Then I should see that I have :count friends
     */
    public function iShouldSeeThatIHaveFriends(int $count) : void
    {
        $found = $this->friendshipsManagementPage->countFriends();

        Assert::eq($count, $found, 'Expected to find %s friends, found %s.');
    }

    /**
     * @Then I should see that I am friend with :first and :second
     */
    public function iShouldSeeThatIAmFriendWith(string ...$names) : void
    {
        $foundNames = $this->friendshipsManagementPage->getDisplayedFriends();

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
        $found = $this->friendshipsManagementPage->countReceivedFriendshipRequests();

        Assert::same($count, $found, 'Expected to find %s received friendship requests, found %s instead.');
    }

    /**
     * @Then it should be from :name
     */
    public function itShouldBeFrom(string $name) : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasReceivedFriendshipRequestFrom($name),
            'Could not find a friendship request from ' . $name
        );
    }

    /**
     * @Then I should see that I have :count sent friendship request(s)
     */
    public function iShouldSeeThatIHaveSentFriendshipRequest(int $count) : void
    {
        $found = $this->friendshipsManagementPage->countSentFriendshipRequests();

        Assert::same($count, $found, 'Expected to find %s sent friendship requests, found %s instead.');
    }

    /**
     * @Then it should be for :name
     */
    public function itShouldBeFor(string $name) : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasSentFriendshipRequestTo($name),
            'Could not find a friendship request sent to ' . $name
        );
    }

    /**
     * @Then I should see that I haven't got any received friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnyReceivedFriendshipRequests() : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasNoReceivedFriendshipRequestsMessage()
        );
    }

    /**
     * @Then I should see that I haven't got any sent friendship requests
     */
    public function iShouldSeeThatIHavenTGotAnySentFriendshipRequests() : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasNoSentFriendshipRequestsMessage()
        );
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

        $this->friendshipsManagementPage->getFriendshipRequestFrom($toFind)->accept();
    }

    /**
     * @Then I should be notified that we've became friends
     */
    public function iShouldBeNotifiedThatWeVeBecameFriends() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Friendship request successfully accepted!', AlertsCheckerInterface::TYPE_SUCCESS)
        );
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

        $this->friendshipsManagementPage->getFriendshipRequestFrom($toFind)->decline();
    }

    /**
     * @Then I should be notified that the request is removed
     */
    public function iShouldBeNotifiedThatTheRequestIsRemoved() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Friendship request successfully declined!', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    /**
     * @Given I delete my friendship with :name
     * @Given I want to stop being a friend with :name
     */
    public function iDeleteMyFriendshipWith(string $name) : void
    {
        /** @var User $friend */
        $friend = $this->storage->get('created_user_' . $name);
        $toFind = $friend->getName();

        $this->friendshipsManagementPage->getFriendship($toFind)->delete();
    }

    /**
     * @Then I should be notified that the friendship is deleted
     */
    public function iShouldBeNotifiedThatTheFriendshipIsDeleted() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Sorry to see broken friendships.', AlertsCheckerInterface::TYPE_SUCCESS),
            'Could not find a notification about deleted friendship.'
        );
    }

    /**
     * @Then I shouldn't see :name in the list of friends anymore
     */
    public function iShouldnTSeeInTheListOfFriendsAnymore(string $name) : void
    {
        Assert::false(
            $this->friendshipsManagementPage->isFriendWith($name)
        );
    }

    /**
     * @When I want to cancel the friendship request
     */
    public function iWantToCancelTheFriendshipRequest() : void
    {
        /** @var FriendshipRequest $request */
        $request = $this->storage->get('current_friendship_request');
        $toFind = $request->getToUser()->getName();

        $this->friendshipsManagementPage->getFriendshipRequestTo($toFind)->cancel();
    }

    /**
     * @Then I should be notified that the request is cancelled
     */
    public function iShouldBeNotifiedThatTheRequestIsCancelled() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Friendship request successfully cancelled!', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    /**
     * @Then I should see a message that I still don't have any friends
     */
    public function iShouldSeeAMessageThatIStillDonTHaveAnyFriends() : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasNoFriendsMessage()
        );
    }

    /**
     * @Then I should see suggestion to add :name as a friend
     */
    public function iShouldSeeSuggestionToAddAsAFriend(string $name) : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasSuggestionToAddAsFriend($name)
        );
    }

    /**
     * @Then I should not see suggestion to add :name as a friend
     */
    public function iShouldNotSeeSuggestionToAddAsAFriend(string $name) : void
    {
        Assert::false(
            $this->friendshipsManagementPage->hasSuggestionToAddAsFriend($name)
        );
    }

    /**
     * @Then I should see a message that there are no friends suggested for me
     */
    public function iShouldSeeAMessageThatThereAreNoFriendsSuggestedForMe() : void
    {
        Assert::true(
            $this->friendshipsManagementPage->hasNoFriendshipSuggestionsMessage()
        );
    }

    /**
     * @When I choose to ignore the suggestion to add :name as a friend
     */
    public function iChooseToIgnoreTheSuggestionToAddAsAFriend(string $name) : void
    {
        $this->friendshipsManagementPage->getFriendshipSuggestion($name)->ignore();
    }
}
