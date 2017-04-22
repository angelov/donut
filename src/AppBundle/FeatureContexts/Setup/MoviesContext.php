<?php

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Movies\Movie;

class MoviesContext implements Context
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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
}
