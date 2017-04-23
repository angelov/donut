<?php

namespace SocNet\Movies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="movie")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id = '';

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string")
     */
    private $plot;

    /**
     * @ORM\ManyToMany(targetEntity="SocNet\Movies\Genre", inversedBy="movies")
     * @ORM\JoinTable(
     *     name="movie_has_genre",
     *     joinColumns={
     *          @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     *      }
     * )
     */
    private $genres;

    /**
     * @ORM\Column(type="string")
     */
    private $poster = '';

    /**
     * @param $genres Genre[]
     */
    public function __construct(string $title, int $year, string $plot = '', array $genres = [])
    {
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
