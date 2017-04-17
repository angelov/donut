<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class BrowsingCommunitiesContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, Storage $storage)
    {
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When I want to browse the communities
     * @When I am browsing the communities
     */
    public function iWantToBrowseTheCommunities() : void
    {
        $url = $this->router->generate('app.communities.index');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see :count listed communities
     */
    public function iShouldSeeListedCommunities(int $count) : void
    {
        $items = $this->session->getPage()->findAll('css', '.community');

        Assert::same(count($items), $count, 'Counted %d communities instead of %s');
    }

    /**
     * @Then I should have an option to join the :name community
     */
    public function iShouldHaveAnOptionToJoinTheCommunity(string $name) : void
    {
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));
        $this->storage->set('current_community_name', $name);

        Assert::true($item->hasButton('Join'));
    }

    /**
     * @Then I should not have an option to join it
     */
    public function iShouldNotHaveAnOptionToJoinIt() : void
    {
        $name = $this->storage->get('current_community_name');
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));

        Assert::false($item->hasButton('Join'));
    }

    /**
     * @Then I should have an option to view the :name community
     */
    public function iShouldHaveAnOptionToViewTheCommunity(string $name) : void
    {
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));
        $this->storage->set('current_community_name', $name);

        Assert::true($item->hasLink('View'));
    }

    /**
     * @Then I should not have an option to view it
     */
    public function iShouldNotHaveAnOptionToViewIt() : void
    {
        $name = $this->storage->get('current_community_name');
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));

        Assert::false($item->hasLink('View'));
    }

    /**
     * @Given I try to join the :name community
     */
    public function iTryToJoinTheCommunity(string $name) : void
    {
        $button = $this->session->getPage()->find('css', sprintf('.community:contains("%s") .btn:contains("Join")', $name));
        $button->press();
    }

    /**
     * @Then I should be notified that I have joined the community
     */
    public function iShouldBeNotifiedThatIHaveJoinedTheCommunity() : void
    {
        Assert::true($this->session->getPage()->hasContent('Successfully joined the community'));
    }

    /**
     * @Then those communities should be :first and :second
     * @Then I should see the :name community
     */
    public function thoseCommunitiesShouldBeAnd(string ...$names) : void
    {
        $found = $this->session->getPage()->findAll('css', '.community-name');
        $found = array_map(function (NodeElement $element) : string {
            return $element->getText();
        }, $found);

        $this->storage->set('current_community_name', $names[count($names)-1]);

        sort($names);
        sort($found);

        Assert::same($names, $found);
    }

    /**
     * @Then it should be described as :description
     */
    public function itShouldBeDescribedAs(string $description) : void
    {
        $community = $this->storage->get('current_community_name');
        $nameElement = $this->session->getPage()->find('css', sprintf('.community .media-body .community-name:contains("%s")', $community));

        $communityElement = $nameElement->getParent()->getParent();

        Assert::true($communityElement->has('css', sprintf('.media-body:contains("%s")', $description)));
    }

    /**
     * @Then I should see that it is created by me
     */
    public function iShouldSeeThatItIsCreatedByMe() : void
    {
        $community = $this->storage->get('current_community_name');
        $nameElement = $this->session->getPage()->find('css', sprintf('.community .media-body .community-name:contains("%s")', $community));

        $communityElement = $nameElement->getParent()->getParent();

        $logged = $this->storage->get('logged_user')->getName();

        Assert::true($communityElement->has('css', sprintf('.media-body:contains("%s")', $logged)));
    }

    /**
     * @Then I should see that the :name community is created by :author
     */
    public function iShouldSeeThatTheCommunityIsCreatedBy(string $name, string $author) : void
    {
        $nameElement = $this->session->getPage()->find('css', sprintf('.community .media-body .community-name:contains("%s")', $name));

        $communityElement = $nameElement->getParent()->getParent();

        Assert::true($communityElement->has('css', sprintf('.media-body:contains("%s")', $author)));
    }

    /**
     * @Then I should see a message that there aren't any existing communities
     */
    public function iShouldSeeAMessageThatThereArenTAnyExistingCommunities() : void
    {
        $message = 'There aren\'t any communities available for you. Want to create one?';

        Assert::true($this->session->getPage()->has('css', sprintf('p:contains("%s")', $message)));
    }
}
