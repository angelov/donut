<?php

namespace SocNet\Movies;

use Doctrine\Common\Collections\ArrayCollection;

class Genre
{
    private $id;

    private $title;

    private $movies;

    public function __construct(string $id, string $title)
    {
        $this->title = $title;
        $this->id = $id;
        $this->movies = new ArrayCollection();
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

    /**
     * @return Movie[]
     */
    public function getMovies() : array
    {
        return $this->movies->getValues();
    }

    public function addMovie(Movie $movie) : void
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addGenre($this);
        }
    }
}
