<?php

namespace Angelov\Donut\Behat\Hooks;

use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

class PurgeDatabase extends RawMinkContext
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase() : void
    {
        $purger = new ORMPurger($this->em);
        $purger->purge();
        $this->em->clear();
    }
}