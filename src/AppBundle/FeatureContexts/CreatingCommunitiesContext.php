<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class CreatingCommunitiesContext implements Context
{
    private $router;
    private $session;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @When I want to create a new community
     */
    public function iWantToCreateANewCommunity() : void
    {
        $url = $this->router->generate('app.communities.create');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @When I specify the name as :name
     * @When I don't specify the name
     */
    public function iSpecifyTheNameAs(string $name = '') : void
    {
        $this->session->getPage()->fillField('Name', $name);
    }

    /**
     * @When I try to create it
     */
    public function iTryToCreateIt() : void
    {
        $this->session->getPage()->pressButton('Submit');
    }

    /**
     * @Then I should be notified that the community is created
     */
    public function iShouldBeNotifiedThatTheCommunityIsCreated() : void
    {
        Assert::true($this->session->getPage()->hasContent('Community was successfully created!'));
    }

    /**
     * @Given I specify the description as :description
     */
    public function iSpecifyTheDescriptionAs(string $description) : void
    {
        $this->session->getPage()->fillField('Description', $description);
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatTheNameIsRequired() : void
    {
        Assert::true(
            $this->session->getPage()->hasContent('Please enter a name for the community.'),
            'Could not find the proper validation message.'
        );
    }
}
