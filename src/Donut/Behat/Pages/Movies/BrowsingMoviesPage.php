<?php

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
