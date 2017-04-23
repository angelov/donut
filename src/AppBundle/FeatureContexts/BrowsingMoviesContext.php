<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;

class BrowsingMoviesContext implements Context
{
    private $session;
    private $router;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @When I want to browse the movies
     */
    public function iWantToBrowseTheMovies() : void
    {
        throw new PendingException();
    }

    /**
     * @Then I should see :count listed movies
     */
    public function iShouldSeeListedMovies(int $count) : void
    {
        throw new PendingException();
    }

    /**
     * @Then those movies should be :first and :second
     */
    public function thoseMoviesShouldBeAnd(string ...$movieTitles) : void
    {
        throw new PendingException();
    }

    /**
     * @Then those movies should be the following
     */
    public function thoseMoviesShouldBeTheFollowing(TableNode $table) : void
    {
        throw new PendingException();
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
        throw new PendingException();
    }
}