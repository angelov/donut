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

namespace spec\Angelov\Donut\Movies;

use Angelov\Donut\Movies\Movie;
use Angelov\Donut\Movies\Genre;
use PhpSpec\ObjectBehavior;

class MovieSpec extends ObjectBehavior
{
    const MOVIE_ID = 'uuid value';
    const MOVIE_TITLE = 'Example Movie';
    const MOVIE_YEAR = 2017;
    const MOVIE_PLOT = 'This is just an example movie';
    const MOVIE_POSTER = 'example.jpg';

    public function let(Genre $genre)
    {
        $this->beConstructedWith(self::MOVIE_ID, self::MOVIE_TITLE, self::MOVIE_YEAR, self::MOVIE_PLOT, [$genre]);

        $genre->addMovie($this)->shouldHaveBeenCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Movie::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::MOVIE_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new id');
        $this->getId()->shouldReturn('new id');
    }

    function it_has_title_by_default()
    {
        $this->getTitle()->shouldReturn(self::MOVIE_TITLE);
    }

    function it_has_mutable_title()
    {
        $newTitle = 'New title';

        $this->setTitle($newTitle);
        $this->getTitle()->shouldReturn($newTitle);
    }

    function it_has_year_by_default()
    {
        $this->getYear()->shouldReturn(self::MOVIE_YEAR);
    }

    function it_has_mutable_year()
    {
        $newYear = 1992;

        $this->setYear($newYear);
        $this->getYear()->shouldReturn($newYear);
    }

    function it_has_plot_by_default()
    {
        $this->getPlot()->shouldReturn(self::MOVIE_PLOT);
    }

    function it_has_mutable_plot()
    {
        $newPlot = 'Another movie plot';

        $this->setPlot($newPlot);
        $this->getPlot()->shouldReturn($newPlot);
    }

    function it_has_no_poster_by_default()
    {
        $this->getPoster()->shouldReturn('');
    }

    function it_has_mutable_poster()
    {
        $this->setPoster(self::MOVIE_POSTER);
        $this->getPoster()->shouldReturn(self::MOVIE_POSTER);
    }

    function it_has_genres_by_default(Genre $genre)
    {
        $this->getGenres()->shouldReturn([$genre]);
    }

    function it_can_have_multple_genres(Genre $genre, Genre $anotherGenre)
    {
        $this->addGenre($anotherGenre);

        $anotherGenre->addMovie($this);
        $this->getGenres()->shouldHaveCount(2);
        $this->getGenres()->shouldContain($genre); // added in the constructor
        $this->getGenres()->shouldContain($anotherGenre);
    }

    function it_does_not_add_existing_genres(Genre $anotherGenre)
    {
        $this->addGenre($anotherGenre);
        $this->addGenre($anotherGenre);

        $this->getGenres()->shouldHaveCount(2); // $anotherGenre plus $genre from constructor
    }
}