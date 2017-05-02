<?php

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Movies\Genre;

class MovieGenresContext implements Context
{
    private $entityManager;
    private $storage;

    public function __construct(EntityManagerInterface $entityManager, StorageInterface $storage)
    {
        $this->entityManager = $entityManager;
        $this->storage = $storage;
    }

    /**
     * @Given there are the following genres:
     */
    public function thereAreTheFollowingGenres(TableNode $table) : void
    {
        foreach ($table as $genre) {
            $genreObj = new Genre($genre['Title']);
            $this->entityManager->persist($genreObj);
            $sg = $this->storage->get('created_genres', []);
            $sg[$genre['Title']] = $genreObj;
            $this->storage->set('created_genres', $sg);
        }

        $this->entityManager->flush();
    }
}
