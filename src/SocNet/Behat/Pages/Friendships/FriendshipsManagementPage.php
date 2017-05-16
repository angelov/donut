<?php

namespace SocNet\Behat\Pages\Friendships;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Page;
use SocNet\Behat\Service\ElementsTextExtractor;

class FriendshipsManagementPage extends Page
{
    protected function getRoute() : string
    {
        return 'app.friends.index';
    }

    public function countFriends() : int
    {
        return count(
            $this->getDocument()->findAll('css', '#friends-list .panel')
        );
    }

    public function getDisplayedFriends() : array
    {
        $list = $this->getDocument()->findAll('css', '#friends-list .user-name');

        return ElementsTextExtractor::fromElements($list);
    }

    public function countReceivedFriendshipRequests() : int
    {
        return count(
            $this->getReceivedFriendshipRequests()
        );
    }

    public function getReceivedFriendshipRequests() : array
    {
        $list = $this->getDocument()->findAll('css', '#received-friendship-requests-list .panel .user-name');

        return ElementsTextExtractor::fromElements($list);
    }

    public function hasReceivedFriendshipRequestFrom(string $name) : bool
    {
        return in_array($name, $this->getReceivedFriendshipRequests(), true);
    }

    public function countSentFriendshipRequests() : int
    {
        return count(
            $this->getSentFriendshipRequests()
        );
    }

    public function getSentFriendshipRequests() : array
    {
        $list = $this->getDocument()->findAll('css', '#sent-friendship-requests-list .panel .user-name');

        return ElementsTextExtractor::fromElements($list);
    }

    public function hasSentFriendshipRequestTo(string $name) : bool
    {
        return in_array($name, $this->getSentFriendshipRequests(), true);
    }

    public function hasNoReceivedFriendshipRequestsMessage() : bool
    {
        return $this->getDocument()->hasContent('0 non-responded friendship requests found.');
    }

    public function hasNoSentFriendshipRequestsMessage() : bool
    {
        return $this->getDocument()->hasContent('You haven\'t sent any friendship requests.');
    }

    public function getFriendshipRequestFrom(string $name) : FriendshipRequestCard
    {
        return new FriendshipRequestCard(
            $this->getDocument()->find('css', sprintf('#received-friendship-requests-list .user-card:contains("%s")', $name))
        );
    }

    public function getFriendshipRequestTo(string $name) : FriendshipRequestCard
    {
        return new FriendshipRequestCard(
            $this->getDocument()->find('css', sprintf('#sent-friendship-requests-list .user-card:contains("%s")', $name))
        );
    }

    public function getFriendship(string $name) : FriendshipCard
    {
        return new FriendshipCard(
            $this->getDocument()->find('css', sprintf('#friends-list .user-card:contains("%s")', $name))
        );
    }

    public function isFriendWith(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#friends-list .user-card .user-name:contains("%s")', $name));
    }

    public function hasNoFriendsMessage() : bool
    {
        return $this->getDocument()->has('css', '#friends-list li:contains("You still don\'t have any friends :(")');
    }

    public function hasSuggestionToAddAsFriend(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#friends-suggestions .user-card .user-name:contains("%s")', $name));
    }

    public function hasNoFriendshipSuggestionsMessage() : bool
    {
        return $this->getDocument()->has('css', '#friends-suggestions li:contains("No suggested friends for you. Sorry.")');
    }

    public function getFriendshipSuggestion(string $name) : FriendshipSuggestionCard
    {
        return new FriendshipSuggestionCard(
            $this->getDocument()->find('css', sprintf('#friends-suggestions .user-card:contains("%s") .btn-ignore-friendship-suggestion', $name))
        );
    }
}
