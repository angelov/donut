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

namespace Angelov\Donut\Behat\Pages\Users;

use Behat\Mink\Element\NodeElement;

class UserCard
{
    private $element;

    public function __construct(NodeElement $element)
    {
        $this->element = $element;
    }

    public function getNumberOfThoughts() : int
    {
        return (int) $this->extractBadgeValue('thoughts');
    }

    public function getNumberOfFriends() : int
    {
        return (int) $this->extractBadgeValue('friends');
    }

    public function getNumberOfMutualFriends() : int
    {
        return (int) $this->extractBadgeValue('mutual friends');
    }

    public function getMutualFriendsNames() : array
    {
        $names = $this->element->find('css', '.mutual-friends-list .mutual-friend-name')->getText();

        return explode(', ', $names);
    }

    public function getEmail() : string
    {
        return $this->element->find('css', '.badge[data-type="email"]')->getText();
    }

    private function extractBadgeValue($badge) : string
    {
        $badge = $this->element->find('css', sprintf('.badge:contains(" %s")', $badge));

        return explode(' ', $badge->getText())[0];
    }

    public function addAsFriend() : void
    {
        $this->element->clickLink('Add friend');
    }

    protected function getElement() : NodeElement
    {
        return $this->element;
    }
}
