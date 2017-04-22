<?php

namespace SocNet\Movies;

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

    public function __construct(string $title, int $year, string $plot = '')
    {
        $this->title = $title;
        $this->year = $year;
        $this->plot = $plot;
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
}
