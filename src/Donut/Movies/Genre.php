<?php

namespace Angelov\Donut\Movies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="movie_genre")
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="Angelov\Donut\Movies\Movie", mappedBy="genres")
     */
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
