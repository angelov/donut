<?php

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Movies\Genre;

class MovieGenresContext implements Context
{
    private $entityManager;
    private $storage;
    private $uuidGenerator;

    public function __construct(EntityManagerInterface $entityManager, StorageInterface $storage, UuidGeneratorInterface $uuidGenerator)
    {
        $this->entityManager = $entityManager;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @Given there are the following genres:
     */
    public function thereAreTheFollowingGenres(TableNode $table) : void
    {
        foreach ($table as $genre) {
            $id = $this->uuidGenerator->generate();

            $genreObj = new Genre($id, $genre['Title']);
            $this->entityManager->persist($genreObj);
            $sg = $this->storage->get('created_genres', []);
            $sg[$genre['Title']] = $genreObj;
            $this->storage->set('created_genres', $sg);
        }

        $this->entityManager->flush();
    }
}
