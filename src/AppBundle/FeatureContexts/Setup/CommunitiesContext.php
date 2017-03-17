<?php

namespace AppBundle\FeatureContexts\Setup;

use AppBundle\Entity\Community;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;

class CommunitiesContext implements Context
{
    private $em;
    private $storage;

    public function __construct(EntityManager $em, Storage $storage)
    {
        $this->em = $em;
        $this->storage = $storage;
    }

    /**
     * @Given there is a community named :name and described as :description
     * @Given there is a community named :name
     */
    public function thereIsACommunityNamedAndDescribedAs(string $name, string $description = '') : void
    {
        $community = new Community();
        $community->setName($name);
        $community->setDescription($description);

        $logged = $this->storage->get('logged_user');
        $community->setAuthor($logged);

        $this->em->persist($community);
        $this->em->flush();
    }

    /**
     * @Given I have joined the :name community
     */
    public function iHaveJoinedTheCommunity(string $name) : void
    {
        $repo = $this->em->getRepository(Community::class);

        /** @var Community $community */
        $community = $repo->findOneBy(['name' => $name]);

        $logged = $this->storage->get('logged_user');
        $community->addMember($logged);

        $this->em->persist($community);
        $this->em->flush();
    }
}
