<?php

namespace SocNet\Behat\Pages\Movies;

use Behat\Mink\Element\NodeElement;
use SocNet\Behat\Pages\Page;

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

        $mapper = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($mapper, $titles);
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
