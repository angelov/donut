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

namespace Angelov\Donut\Behat\Pages\Friendships;

use Behat\Mink\Element\NodeElement;
use Angelov\Donut\Behat\Pages\Page;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

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
