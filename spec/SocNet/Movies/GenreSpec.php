<?php

namespace spec\SocNet\Movies;

use SocNet\Movies\Genre;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SocNet\Movies\Movie;

class GenreSpec extends ObjectBehavior
{
    const GENRE_TITLE = 'Comedy';

    function let()
    {
        $this->beConstructedWith(self::GENRE_TITLE);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Genre::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
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
