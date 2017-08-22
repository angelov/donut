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

use Angelov\Donut\Movies\Genre;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Movies\Movie;

class GenreSpec extends ObjectBehavior
{
    const GENRE_ID = 'uuid value';
    const GENRE_TITLE = 'Comedy';

    function let()
    {
        $this->beConstructedWith(self::GENRE_ID, self::GENRE_TITLE);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Genre::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::GENRE_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('new');
        $this->getId()->shouldReturn('new');
    }

    function it_has_title_by_default()
    {
        $this->getTitle()->shouldReturn(self::GENRE_TITLE);
    }

    function it_has_mutable_title()
    {
        $newTitle = 'Horror';

        $this->setTitle($newTitle);
        $this->getTitle()->shouldReturn($newTitle);
    }

    function it_has_no_movies_by_default()
    {
        $this->getMovies()->shouldReturn([]);
    }

    function it_can_contain_multiple_movies(Movie $first, Movie $second)
    {
        $this->addMovie($first);
        $this->addMovie($second);

        $first->addGenre($this)->shouldHaveBeenCalled();
        $second->addGenre($this)->shouldHaveBeenCalled();

        $this->getMovies()->shouldHaveCount(2);
        $this->getMovies()->shouldContain($first);
        $this->getMovies()->shouldContain($second);
    }

    function it_does_not_add_existing_movies(Movie $movie)
    {
        $this->addMovie($movie);
        $this->addMovie($movie);

        $this->getMovies()->shouldHaveCount(1);
    }
}
