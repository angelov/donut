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

namespace Angelov\Donut\Behat\Pages\Thoughts;

use Angelov\Donut\Behat\Pages\Page;

class HomePage extends Page
{
    protected function getRoute(): string
    {
        return 'app.thoughts.index';
    }

    public function specifyThoughtContent(string $content) : void
    {
        $this->getDocument()->fillField('Content', $content);
    }

    public function shareThought() : void
    {
        $this->getDocument()->pressButton('Submit');
    }

    public function containsThought(string $thought) : bool
    {
        return $this->getDocument()->has('css', sprintf('.thought pre:contains("%s")', $thought));
    }

    public function countThoughtsFromAuthor(string $author) : int
    {
        $thoughts = $this->getDocument()->findAll('css', sprintf('.thought:contains("by %s")', $author));

        return count($thoughts);
    }

    /**
     * @todo create an ThoughtCard element?
     * @psalm-suppress PossiblyNullReference
     */
    public function deleteThought(string $thought) : void
    {
        $thought = $this->getDocument()->find('css', sprintf('pre:contains("%s")', $thought));

        $thought->getParent()->pressButton('delete');
    }
}
