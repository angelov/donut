<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Places\City;

class CitiesContext implements Context
{
    private $entityManager;
    private $storage;

    public function __construct(EntityManagerInterface $entityManager, Storage $storage)
    {
        $this->entityManager = $entityManager;
        $this->storage = $storage;
    }

    /**
     * @Given there are cities named :first and :second
     */
    public function thereAreCitiesNamedSkopjeAndOhrid(string... $names)
    {
        foreach ($names as $name) {
            $city = new City($name);
            $this->entityManager->persist($city);
        }

        $this->entityManager->flush();
    }
}