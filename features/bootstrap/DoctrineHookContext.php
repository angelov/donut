<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class DoctrineHookContext extends RawMinkContext implements KernelAwareContext
{
    /** @var KernelInterface $kernel */
    private $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $em = $this->kernel->getContainer()->get('doctrine.orm.default_entity_manager');
        $purger = new ORMPurger($em);
        $purger->purge();
        $em->clear();
    }
}