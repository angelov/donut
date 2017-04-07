<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Communities\Community;
use AppBundle\Entity\User;
use AppBundle\FeatureContexts\Storage;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
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
        $logged = $this->storage->get('logged_user');

        $this->createCommunity($name, $description, $logged);
    }

    /**
     * @Given he has created the :name community
     */
    public function heHasCreatedTheCommunity(string $name) : void
    {
        $author = $this->storage->get('last_created_user');

        $this->createCommunity($name, '', $author);
    }

    private function createCommunity(string $name, string $description, User $author) : Community
    {
        $community = new Community();
        $community->setName($name);
        $community->setDescription($description);

        $community->setAuthor($author);

        $this->em->persist($community);
        $this->em->flush();

        $this->storage->set('created_community', $community);
        $this->storage->set('community_' . $name, $community);

        return $community;
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
        /** @var \SocNet\Communities\Community $community */
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

    /**
     * @Given nobody hasn't created any community yet
     */
    public function nobodyHasnTCreatedAnyCommunityYet() : void
    {
        // do nothing
    }
}
