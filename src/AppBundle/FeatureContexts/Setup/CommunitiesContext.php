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

        $this->storage->set('created_community', $community);
        $this->storage->set('community_' . $name, $community);
    }

    /**
     * @Given I have joined the :name community
     */
    public function iHaveJoinedTheCommunity(string $name) : void
    {
        $community = $this->storage->get('community_' . $name);

        $logged = $this->storage->get('logged_user');
        $community->addMember($logged);

        $this->em->persist($community);
        $this->em->flush();
    }

    /**
     * @Given I have joined it
     */
    public function iHaveJoinedIt() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('created_community');
        $user = $this->storage->get('logged_user');

        $community->addMember($user);

        $this->em->flush();
    }

    /**
     * @Given I haven't joined it
     */
    public function iHavenTJoinedIt() : void
    {
        // do nothing
    }

    /**
     * @Given (s)he has (also) joined it
     */
    public function heAlsoHasJoinedIt() : void
    {
        $community = $this->storage->get('created_community');
        $user = $this->storage->get('last_created_user');

        $community->addMember($user);

        $this->em->flush();
    }
}
