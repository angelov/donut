<?php

namespace spec\SocNet\Movies;

use SocNet\Movies\Movie;
use SocNet\Movies\Genre;
use PhpSpec\ObjectBehavior;

class MovieSpec extends ObjectBehavior
{
    const MOVIE_TITLE = 'Example Movie';
    const MOVIE_YEAR = 2017;
    const MOVIE_PLOT = 'This is just an example movie';
    const MOVIE_POSTER = 'example.jpg';

    public function let(Genre $genre)
    {
        $this->beConstructedWith(self::MOVIE_TITLE, self::MOVIE_YEAR, self::MOVIE_PLOT, [$genre]);

        $genre->addMovie($this)->shouldHaveBeenCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Movie::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn('');
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