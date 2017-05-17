<?php

namespace SocNet\Movies;

use Doctrine\Common\Collections\ArrayCollection;

class Movie
{
    private $id;

    private $title;

    private $year;

    private $plot;

    private $genres;

    private $poster = '';

    /**
     * @param $genres Genre[]
     */
    public function __construct(string $id, string $title, int $year, string $plot = '', array $genres = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->year = $year;
        $this->plot = $plot;
        $this->genres = new ArrayCollection($genres);

        foreach ($genres as $genre) {
            $genre->addMovie($this);
        }
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getYear() : int
    {
        return $this->year;
    }

    public function setYear(int $year) : void
    {
        $this->year = $year;
    }

    public function getPlot() : string
    {
        return $this->plot;
    }

    public function setPlot(string $plot) : void
    {
        $this->plot = $plot;
    }

    public function getGenres() : array
    {
        return $this->genres->getValues();
    }

    public function addGenre(Genre $genre) : void
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addMovie($this);
        }
    }

    public function getPoster() : string
    {
        return $this->poster;
    }

    public function setPoster(string $poster) : void
    {
        $this->poster = $poster;
    }
}
