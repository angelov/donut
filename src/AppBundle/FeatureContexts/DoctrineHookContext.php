<?php

namespace AppBundle\FeatureContexts;

use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

class DoctrineHookContext extends RawMinkContext
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $purger = new ORMPurger($this->em);
        $purger->purge();
        $this->em->clear();
    }
}