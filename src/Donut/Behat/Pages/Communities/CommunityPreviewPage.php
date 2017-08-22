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

use Behat\Mink\Element\NodeElement;
use Angelov\Donut\Behat\Pages\Page;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

class CommunityPreviewPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.show';
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getDescription() : string
    {
        return $this->getDocument()->find('css', '#community-description')->getText();
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getAuthorName() : string
    {
        return $this->getDocument()->find('css', '#community-author')->getText();
    }

    /**
     * @psalm-suppress PossiblyNullReference
     */
    public function getCreationDate() : string
    {
        return $this->getDocument()->find('css', '#community-creation-date')->getText();
    }

    public function hasMembersList() : bool
    {
        return $this->getDocument()->has('css', 'ul.community-members');
    }

    public function getDisplayedMembers() : array
    {
        $elements = $this->getDocument()->findAll('css', 'ul.community-members li');

        return ElementsTextExtractor::fromElements($elements);
    }

    public function countDisplayedMembers() : int
    {
        return count(
            $this->getDocument()->findAll('css', 'ul.community-members li')
        );
    }

    public function join() : void
    {
        $this->getDocument()->pressButton('Join');
    }

    public function leave() : void
    {
        $this->getDocument()->pressButton('Leave');
    }
}
