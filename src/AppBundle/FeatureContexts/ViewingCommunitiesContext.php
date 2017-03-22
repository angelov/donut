<?php

namespace AppBundle\FeatureContexts;

use AppBundle\Entity\Community;
use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

class ViewingCommunitiesContext implements Context
{
    private $em;
    private $session;
    private $router;
    private $storage;

    public function __construct(EntityManager $em, Session $session, RouterInterface $router, Storage $storage)
    {
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When I want to view the :name community
     * @When I am viewing the :name community
     */
    public function iWantToViewTheCommunity(string $name) : void
    {
        $community = $this->em->getRepository(Community::class)->findOneBy(['name' => $name]);
        $this->openCommunityPage($community);
    }

    /**
     * @When I want to view it
     * @When I am viewing it
     */
    public function iWantToViewIt() : void
    {
        $community = $this->storage->get('created_community');
        $this->openCommunityPage($community);
    }

    private function openCommunityPage(Community $community) : void
    {
        $url = $this->router->generate('app.communities.show', ['id' => $community->getId()]);
        $this->session->getDriver()->visit($url);
        $this->storage->set('current_community', $community);
    }

    /**
     * @Then I should see its description
     */
    public function iShouldSeeItsDescription() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $description = $this->session->getPage()->find('css', '#community-description')->getText();

        if ($description != $community->getDescription()) {
            throw new \Exception(sprintf(
                'The displayed community description is not correct. Got [%s] instead of [%s]',
                $description,
                $community->getDescription()
            ));
        }
    }

    /**
     * @Then I should see its author
     */
    public function iShouldSeeItsAuthor() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $author = $this->session->getPage()->find('css', '#community-author')->getText();

        if ($author != $community->getAuthor()->getName()) {
            throw new \Exception(sprintf(
                'The displayed community author is not correct. Got [%s] instead of [%s]',
                $author,
                $community->getAuthor()->getName()
            ));
        }
    }

    /**
     * @Then I should see its creation date
     */
    public function iShouldSeeItsCreationDate() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $creationDate = $this->session->getPage()->find('css', '#community-creation-date')->getText();

        if ($creationDate != $community->getCreatedAt()->format('Y-m-d')) {
            throw new \Exception(sprintf(
                'The displayed community creation date is not correct. Got [%s] instead of [%s]',
                $creationDate,
                $community->getCreatedAt()->format('Y-m-d')
            ));
        }
    }

    /**
     * @Then I should see a list of its members
     */
    public function iShouldSeeAListOfItsMembers() : void
    {
        $found = $this->session->getPage()->has('css', 'ul.community-members');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I shouldn't see a list of its members
     */
    public function iShouldnTSeeAListOfItsMembers() : void
    {
        $found = $this->session->getPage()->has('css', 'ul.community-members');

        if ($found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should be part of it
     */
    public function iShouldBePartOfIt() : void
    {
        $list = $this->session->getPage()->find('css', 'ul.community-members');
        $user = $this->storage->get('logged_user');

        $found = $list->findAll('css', sprintf('li:contains("%s")', $user->getName()));

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then it should have :count member(s)
     */
    public function itShouldHaveMember(int $count) : void
    {
        $list = $this->session->getPage()->find('css', 'ul.community-members');
        $members = $list->findAll('css', 'li');

        if (count($members) !== $count) {
            throw new \Exception();
        }
    }

    /**
     * @When I try to join it
     */
    public function iTryToJoinIt() : void
    {
        $this->session->getPage()->pressButton('Join');
    }

    /**
     * @Then I should be notified that I have joined it
     */
    public function iShouldBeNotifiedThatIHaveJoinedIt() : void
    {
        $found = $this->session->getPage()->hasContent('Successfully joined the community');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @When I try to leave it
     */
    public function iTryToLeaveIt() : void
    {
        $this->session->getPage()->pressButton('Leave');
    }

    /**
     * @Then I should be notified that I have left it
     */
    public function iShouldBeNotifiedThatIHaveLeftIt() : void
    {
        $found = $this->session->getPage()->hasContent('Successfully left the community');

        if (!$found) {
            throw new \Exception();
        }
    }
}
