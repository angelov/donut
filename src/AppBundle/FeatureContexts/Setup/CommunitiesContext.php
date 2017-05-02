<?php

namespace AppBundle\FeatureContexts\Setup;

use SocNet\Behat\Service\Storage\StorageInterface;
use SocNet\Communities\Community;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Users\User;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;

class CommunitiesContext implements Context
{
    private $em;
    private $storage;
    private $uuidGenerator;

    public function __construct(EntityManager $em, StorageInterface $storage, UuidGeneratorInterface $uuidGenerator)
    {
        $this->em = $em;
        $this->storage = $storage;
        $this->uuidGenerator = $uuidGenerator;
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
        $community = new Community($this->uuidGenerator->generate(), $name, $author, $description);

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
