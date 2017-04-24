<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Movies\Genre;
use SocNet\Movies\Movie;

class MoviesContext implements Context
{
    private $em;
    private $storage;

    public function __construct(EntityManagerInterface $entityManager, Storage $storage)
    {
        $this->em = $entityManager;
        $this->storage = $storage;
    }

    /**
     * @Given there is a movie titled :title
     */
    public function thereIsAMovieTitled(string $title) : void
    {
        $movie = new Movie($title, 2017, 'Example plot');

        $this->em->persist($movie);
        $this->em->flush();
    }

    /**
     * @Given there are the following movies
     */
    public function thereAreTheFollowingMovies(TableNode $table)
    {
        foreach ($table as $movie) {

            $genres = explode(', ', $movie['Genres']);
            $genres = array_map(function($genre) {
                return $this->storage->get('created_genres')[$genre];
            }, $genres);

            $movie = new Movie(
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
    public function thereAreMoviesFromTheActionGenreReleasedIn(int $count, string $genre = '', int $year = 2017)
    {
        $genres = $this->storage->get('created_genres');

        /** @var Genre $genre */
        $genre = ($genre !== '') ? $genres[$genre] : $genres[array_rand($genres)];

        for ($i=0; $i<$count; $i++) {

            $movie = new Movie(
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
