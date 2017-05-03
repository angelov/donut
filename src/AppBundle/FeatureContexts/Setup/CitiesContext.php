<?php

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Places\City;

class CitiesContext implements Context
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
     * @Given there are cities named :first and :second
     */
    public function thereAreCitiesNamedSkopjeAndOhrid(string... $names)
    {
        foreach ($names as $name) {
            $id = $this->uuidGenerator->generate();
            $city = new City($id, $name);
            $this->entityManager->persist($city);
        }

        $this->entityManager->flush();
    }
}
