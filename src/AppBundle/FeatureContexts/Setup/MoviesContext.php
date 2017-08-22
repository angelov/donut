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

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Core\UuidGenerator\UuidGeneratorInterface;
use Angelov\Donut\Movies\Genre;
use Angelov\Donut\Movies\Movie;

class MoviesContext implements Context
{
    private $em;
    private $storage;
    private $uuidGenerator;

    public function __construct(EntityManagerInterface $entityManager, StorageInterface $storage, UuidGeneratorInterface $uuidGenerator)
    {
        $this->em = $entityManager;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @Given there is a movie titled :title
     */
    public function thereIsAMovieTitled(string $title) : void
    {
        $id = $this->uuidGenerator->generate();
        $movie = new Movie($id, $title, 2017, 'Example plot');

        $this->em->persist($movie);
        $this->em->flush();
    }

    /**
     * @Given there are the following movies
     */
    public function thereAreTheFollowingMovies(TableNode $table) : void
    {
        foreach ($table as $movie) {

            $genres = explode(', ', $movie['Genres']);
            $genres = array_map(function($genre) : Genre {
                return $this->storage->get('created_genres')[$genre];
            }, $genres);
            $id = $this->uuidGenerator->generate();

            $movie = new Movie(
                $id,
                $movie['Title'],
                $movie['Year'],
                '',
                $genres
            );

            $this->em->persist($movie);
        }

        $this->em->flush();
    }

    /**
     * @Given there are :count movies from the :genre genre released in :year
     * @Given there are :count movies from the :genre genre
     * @Given there are :count movies
     */
    public function thereAreMoviesFromTheActionGenreReleasedIn(int $count, string $genre = '', int $year = 2017) : void
    {
        $genres = $this->storage->get('created_genres');

        /** @var Genre $genre */
        $genre = ($genre !== '') ? $genres[$genre] : $genres[array_rand($genres)];

        for ($i=0; $i<$count; $i++) {

            $id = $this->uuidGenerator->generate();
            $movie = new Movie(
                $id,
                sprintf('%s movie #%d', $genre->getTitle(), $i),
                $year,
                '',
                [$genre]
            );

            $this->em->persist($movie);
        }

        $this->em->flush();
    }
}
