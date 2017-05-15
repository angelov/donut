<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use SocNet\Behat\Pages\Communities\CreateCommunityPage;
use SocNet\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use SocNet\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
use Webmozart\Assert\Assert;

class CreatingCommunitiesContext implements Context
{
    private $createCommunityPage;
    private $alertsChecker;
    private $validationErrorsChecker;

    public function __construct(
        CreateCommunityPage $createCommunityPage,
        AlertsCheckerInterface $alertsChecker,
        ValidationErrorsCheckerInterface $validationErrorsChecker
    ) {
        $this->createCommunityPage = $createCommunityPage;
        $this->alertsChecker = $alertsChecker;
        $this->validationErrorsChecker = $validationErrorsChecker;
    }

    /**
     * @When I want to create a new community
     */
    public function iWantToCreateANewCommunity() : void
    {
        $this->createCommunityPage->open();
    }

    /**
     * @When I specify the name as :name
     * @When I don't specify the name
     */
    public function iSpecifyTheNameAs(string $name = '') : void
    {
        $this->createCommunityPage->specifyName($name);
    }

    /**
     * @When I try to create it
     */
    public function iTryToCreateIt() : void
    {
        $this->createCommunityPage->create();
    }

    /**
     * @Then I should be notified that the community is created
     */
    public function iShouldBeNotifiedThatTheCommunityIsCreated() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Community was successfully created!', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    /**
     * @Given I specify the description as :description
     */
    public function iSpecifyTheDescriptionAs(string $description) : void
    {
        $this->createCommunityPage->specifyDescription($description);
    }

    /**
     * @Then I should be notified that the name is required
     */
    public function iShouldBeNotifiedThatTheNameIsRequired() : void
    {
        Assert::true(
            $this->validationErrorsChecker->checkMessageForField('name', 'Please enter a name for the community.'),
            'Could not find the proper validation message.'
        );
    }
}
