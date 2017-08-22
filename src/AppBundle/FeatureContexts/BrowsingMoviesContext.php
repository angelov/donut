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

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Angelov\Donut\Behat\Pages\Movies\BrowsingMoviesPage;
use Webmozart\Assert\Assert;

class BrowsingMoviesContext implements Context
{
    private $browsingMoviesPage;

    public function __construct(BrowsingMoviesPage $browsingMoviesPage)
    {
        $this->browsingMoviesPage = $browsingMoviesPage;
    }

    /**
     * @When I want to browse the movies
     */
    public function iWantToBrowseTheMovies() : void
    {
        $this->browsingMoviesPage->open();
    }

    /**
     * @Then I should see :count listed movies
     */
    public function iShouldSeeListedMovies(int $count) : void
    {
        Assert::eq(
            $count, $this->browsingMoviesPage->countDisplayedMovies(), 'Expected to find %s movies, found %s.'
        );
    }

    /**
     * @Then those movies should be the following
     */
    public function thoseMoviesShouldBeTheFollowing(TableNode $table) : void
    {
        $expected = array_map(function ($row) : string {
            return $row[0];
        }, $table->getRows());
        array_shift($expected);

        $found = $this->browsingMoviesPage->getDisplayedMovieTitles();

        sort($expected);
        sort($found);

        $expected = implode(', ', $expected);
        $found = implode(', ', $found);

        Assert::same($expected, $found);
    }

    /**
     * @When I don't specify any filters
     */
    public function iDonTSpecifyAnyFilters() : void
    {
        // do nothing
    }

    /**
     * @When I choose the :genre genre
     * @When I choose the :first and :second genres
     */
    public function iChooseTheGenre(string ...$genres) : void
    {
        foreach ($genres as $genre) {
            $this->browsingMoviesPage->checkGenre($genre);
        }

        $this->browsingMoviesPage->applyFilters();
    }

    /**
     * @When I choose the :period period
     */
    public function iChooseThePeriod(string $period) : void
    {
        $this->browsingMoviesPage->choosePeriod($period);
        $this->browsingMoviesPage->applyFilters();
    }
}
