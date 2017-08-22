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

namespace Angelov\Donut\Movies;

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
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     */
    private $id;

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
     * @ORM\ManyToMany(targetEntity="Angelov\Donut\Movies\Genre", inversedBy="movies")
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
