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

namespace Angelov\Donut\Behat\Pages\Movies;

use Angelov\Donut\Behat\Pages\Page;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;

class BrowsingMoviesPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.movies.index';
    }

    public function countDisplayedMovies() : int
    {
        return count($this->getDisplayedMovieTitles());
    }

    public function getDisplayedMovieTitles() : array
    {
        $titles = $this->getDocument()->findAll('css', '.movie-title');

        return ElementsTextExtractor::fromElements($titles);
    }

    public function checkGenre(string $genre) : void
    {
        $this->getDocument()->find('css', sprintf('.checkbox:contains("%s") label input', $genre))->check();
    }

    public function applyFilters() : void
    {
        $this->getDocument()->find('css', 'button:contains("Apply filters")')->press();
    }

    public function choosePeriod(string $period) : void
    {
        $btn = $this->getDocument()->findField($period);
        $opt = $btn->getAttribute('value');

        $this->getDocument()->find('css', sprintf('.radio-inline:contains("%s") input', $period))->selectOption($opt);
    }
}
