<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;

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

        if (count($items) !== $count) {
            throw new \Exception(sprintf(
                'Counted %d communities instead of %d',
                count($items),
                $count
            ));
        }
    }

    /**
     * @Then I should have an option to join the :name community
     */
    public function iShouldHaveAnOptionToJoinTheCommunity(string $name) : void
    {
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));
        $this->storage->set('current_community_name', $name);

        if (!$item->hasButton('Join')) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should not have an option to join it
     */
    public function iShouldNotHaveAnOptionToJoinIt() : void
    {
        $name = $this->storage->get('current_community_name');
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));

        if ($item->hasButton('Join')) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should have an option to view the :name community
     */
    public function iShouldHaveAnOptionToViewTheCommunity(string $name) : void
    {
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));
        $this->storage->set('current_community_name', $name);

        if (!$item->hasLink('View')) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should not have an option to view it
     */
    public function iShouldNotHaveAnOptionToViewIt() : void
    {
        $name = $this->storage->get('current_community_name');
        $item = $this->session->getPage()->find('css', sprintf('.community:contains("%s")', $name));

        if ($item->hasLink('View')) {
            throw new \Exception();
        }
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
        $found = $this->session->getPage()->hasContent('Successfully joined the community');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Given those communities should be :first and :second
     */
    public function thoseCommunitiesShouldBeAnd(string ...$names) : void
    {
        $found = $this->session->getPage()->findAll('css', '.community-name');
        $found = array_map(function (NodeElement $element) {
            return $element->getText();
        }, $found);

        sort($names);
        sort($found);

        if ($names !== $found) {
            throw new \Exception();
        }
    }
}
