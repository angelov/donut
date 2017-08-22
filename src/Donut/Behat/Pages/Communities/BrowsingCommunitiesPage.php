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

namespace Angelov\Donut\Behat\Pages\Communities;

use Angelov\Donut\Behat\Pages\Page;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

class BrowsingCommunitiesPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.index';
    }

    public function countDisplayedCommunities() : int
    {
        $communities = $this->getDocument()->findAll('css', '.community');

        return count($communities);
    }

    public function getCommunityCard(string $name) : CommunityCard
    {
        return new CommunityCard(
            // sprintf('.community .media-body .community-name:contains("%s")
            $this->getDocument()->find('css', sprintf('.community:contains("%s")', $name))
        );
    }

    public function joinCommunity(string $name) : void
    {
        $this->getCommunityCard($name)->join();
    }

    public function getDisplayedCommunityNames() : array
    {
        $elements = $this->getDocument()->findAll('css', '.community-name');

        return ElementsTextExtractor::fromElements($elements);
    }

    public function hasNoCommunitiesMessage() : bool
    {
        $message = 'There aren\'t any communities available for you. Want to create one?';

        return $this->getDocument()->has('css', sprintf('p:contains("%s")', $message));
    }
}
