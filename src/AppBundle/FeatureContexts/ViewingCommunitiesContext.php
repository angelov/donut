<?php

namespace AppBundle\FeatureContexts;

use SocNet\Communities\Community;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class ViewingCommunitiesContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(EntityManager $em, Session $session, RouterInterface $router, Storage $storage)
    {
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
        $community = $this->storage->get('community_' . $name);
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
        /** @var \SocNet\Communities\Community $community */
        $community = $this->storage->get('current_community');
        $description = $this->session->getPage()->find('css', '#community-description')->getText();

        Assert::same($community->getDescription(), $description, 'The displayed community description is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see its author
     */
    public function iShouldSeeItsAuthor() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $author = $this->session->getPage()->find('css', '#community-author')->getText();

        Assert::same($author, $community->getAuthor()->getName(), 'The displayed community author is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see its creation date
     */
    public function iShouldSeeItsCreationDate() : void
    {
        /** @var \SocNet\Communities\Community $community */
        $community = $this->storage->get('current_community');
        $creationDate = $this->session->getPage()->find('css', '#community-creation-date')->getText();

        $expected = $community->getCreatedAt()->format('Y-m-d');

        Assert::same($creationDate, $expected, 'The displayed community creation date is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see a list of its members
     */
    public function iShouldSeeAListOfItsMembers() : void
    {
        Assert::true($this->session->getPage()->has('css', 'ul.community-members'));
    }

    /**
     * @Then I shouldn't see a list of its members
     */
    public function iShouldnTSeeAListOfItsMembers() : void
    {
        Assert::false($this->session->getPage()->has('css', 'ul.community-members'));
    }

    /**
     * @Then I should be part of it
     */
    public function iShouldBePartOfIt() : void
    {
        $list = $this->session->getPage()->find('css', 'ul.community-members');
        $user = $this->storage->get('logged_user');

        Assert::true($list->has('css', sprintf('li:contains("%s")', $user->getName())));
    }

    /**
     * @Then it should have :count member(s)
     */
    public function itShouldHaveMember(int $count) : void
    {
        $list = $this->session->getPage()->find('css', 'ul.community-members');
        $members = $list->findAll('css', 'li');

        Assert::same($count, count($members));
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
        Assert::true($this->session->getPage()->hasContent('Successfully joined the community'));
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
        Assert::true($this->session->getPage()->hasContent('Successfully left the community'));
    }
}
