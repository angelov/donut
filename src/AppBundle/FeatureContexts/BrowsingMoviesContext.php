<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
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
    public function iWantToBrowseTheMovies()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see :count listed movies
     */
    public function iShouldSeeListedMovies(int $count)
    {
        throw new PendingException();
    }

    /**
     * @Given those movies should be :first and :second
     */
    public function thoseMoviesShouldBeAnd(string ...$movieTitles)
    {
        throw new PendingException();
    }
}
