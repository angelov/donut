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
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

class BrowsingUsersPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.users.index';
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getUserCard(string $name) : UserCard
    {
        $card = $this->getDocument()->find('css', sprintf('#users-list .user-card:contains("%s")', $name));

        // @todo throw an exception if user is not found and remove psalm suppression

        return new UserCard($card);
    }

    public function countDisplayedUsers() : int
    {
        return count($this->getDocument()->findAll('css', '#users-list .user-card'));
    }

    public function getDisplayedUserNames() : array
    {
        $found = $this->getDocument()->findAll('css', '#users-list .user-card .user-name');

        return ElementsTextExtractor::fromElements($found);
    }
}
