<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use SocNet\Behat\Pages\Movies\BrowsingMoviesPage;
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
