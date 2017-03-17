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

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
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
        $items = $this->session->getPage()->findAll('css', '.community');

        /** @var NodeElement $item */
        foreach ($items as $item) {

            $currentName = $item->find('css', '.community-name')->getText();

            if ($currentName !== $name) {
                continue;
            }

            if ($item->hasButton('Join')) {
                return;
            }

        }

        throw new \Exception();
    }

    /**
     * @Then I should have an option to view the :name community
     */
    public function iShouldHaveAnOptionToViewTheCommunity(string $name) : void
    {
        $items = $this->session->getPage()->findAll('css', '.community');

        /** @var NodeElement $item */
        foreach ($items as $item) {

            $currentName = $item->find('css', '.community-name')->getText();

            if ($currentName !== $name) {
                continue;
            }

            if ($item->hasLink('View')) {
                return;
            }

        }

        throw new \Exception();
    }

    /**
     * @Given I try to join the :name community
     */
    public function iTryToJoinTheCommunity(string $name) : void
    {
        $items = $this->session->getPage()->findAll('css', '.community');

        /** @var NodeElement $item */
        foreach ($items as $item) {

            $currentName = $item->find('css', '.community-name')->getText();

            if ($currentName === $name) {
                $item->pressButton('Join');
                return;
            }
        }

        throw new \Exception();
    }

    /**
     * @Then I should be notified that I have joined the community
     */
    public function iShouldBeNotifiedThatIHaveJoinedTheCommunity()
    {
        $found = $this->session->getPage()->hasContent('Successfully joined the community');

        if (!$found) {
            throw new \Exception();
        }
    }
}
