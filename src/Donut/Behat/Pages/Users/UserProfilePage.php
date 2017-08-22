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

use Angelov\Donut\Behat\Pages\Page;

class UserProfilePage extends Page
{
    protected function getRoute(): string
    {
        return 'app.users.show';
    }

    public function countFriends() : int
    {
        $list = $this->getDocument()->findAll('css', '#friends-list li a');

        return count($list);
    }

    public function friendsListContainsUser(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#friends-list li a:contains("%s")', $name));
    }

    public function countMutualFriends() : int
    {
        $mutual = $this->getDocument()->findAll('css', '#mutual-friends-list li a');

        return count($mutual);
    }

    public function mutualFriendsListContainsUser(string $name) : bool
    {
        return $this->getDocument()->has('css', sprintf('#mutual-friends-list li a:contains("%s")', $name));
    }

    public function countThoughts() : int
    {
        return count($this->getDocument()->findAll('css', '#thoughts-list pre'));
    }

    public function hasNoFriendsMessage() : bool
    {
        return (bool) $this->getDocument()->has('css', '#friends-list li:contains("The user has no friends.")');
    }

    public function hasNoMutualFriendsMessage() : bool
    {
        return $this->getDocument()->has('css', '#mutual-friends-list li:contains("You don\'t have any mutual friends.")');
    }

    public function hasNoSharedThoughtsMessage() : bool
    {
        return $this->getDocument()->has('css', '#thoughts-list p:contains("The user hasn\'t shared any thoughts yet.")');
    }
}
