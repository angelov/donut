<?php

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
