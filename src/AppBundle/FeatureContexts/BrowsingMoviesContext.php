<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

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
        $url = $this->router->generate('app.movies.index');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see :count listed movies
     */
    public function iShouldSeeListedMovies(int $count) : void
    {
        $found = $this->session->getPage()->findAll('css', '.movie-title');

        Assert::same($count, count($found), 'Expected to find %s movies, found %s.');
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

        $found = $this->session->getPage()->findAll('css', '.movie-title');
        $found = array_map(function (NodeElement $el) : string {
            return $el->getText();
        }, $found);

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
            $this->session->getPage()->find('css', sprintf('.checkbox:contains("%s") label input', $genre))->check();
        }

        $this->session->getPage()->find('css', 'button:contains("Apply filters")')->press();
    }

    /**
     * @When I choose the :period period
     */
    public function iChooseThePeriod(string $period) : void
    {
        $btn = $radioButton = $this->session->getPage()->findField($period);
        $opt = $btn->getAttribute('value');

        $this->session->getPage()->find('css', sprintf('.radio-inline:contains("%s") input', $period))->selectOption($opt);

        $this->session->getPage()->find('css', 'button:contains("Apply filters")')->press();
    }
}
