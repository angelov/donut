<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Angelov\Donut\Behat\Pages\Communities\BrowsingCommunitiesPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Webmozart\Assert\Assert;

class BrowsingCommunitiesContext implements Context
{
    private $storage;
    private $browsingCommunitiesPage;
    private $alertsChecker;

    public function __construct(StorageInterface $storage, BrowsingCommunitiesPage $browsingCommunitiesPage, AlertsCheckerInterface $alertsChecker)
    {
        $this->storage = $storage;
        $this->browsingCommunitiesPage = $browsingCommunitiesPage;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When I want to browse the communities
     * @When I am browsing the communities
     */
    public function iWantToBrowseTheCommunities() : void
    {
        $this->browsingCommunitiesPage->open();
    }

    /**
     * @Then I should see :count listed communities
     */
    public function iShouldSeeListedCommunities(int $count) : void
    {
        $found = $this->browsingCommunitiesPage->countDisplayedCommunities();

        Assert::eq($found, $count, 'Counted %d communities instead of %s');
    }

    /**
     * @Then I should have an option to join the :name community
     */
    public function iShouldHaveAnOptionToJoinTheCommunity(string $name) : void
    {
        $this->storage->set('current_community_name', $name);

        Assert::true(
            $this->browsingCommunitiesPage->getCommunityCard($name)->hasJoinButton()
        );
    }

    /**
     * @Then I should not have an option to join it
     */
    public function iShouldNotHaveAnOptionToJoinIt() : void
    {
        $name = $this->storage->get('current_community_name');

        Assert::false(
            $this->browsingCommunitiesPage->getCommunityCard($name)->hasJoinButton()
        );
    }

    /**
     * @Then I should have an option to view the :name community
     */
    public function iShouldHaveAnOptionToViewTheCommunity(string $name) : void
    {
        $this->storage->set('current_community_name', $name);

        Assert::true(
            $this->browsingCommunitiesPage->getCommunityCard($name)->hasViewButton()
        );
    }

    /**
     * @Then I should not have an option to view it
     */
    public function iShouldNotHaveAnOptionToViewIt() : void
    {
        $name = $this->storage->get('current_community_name');

        Assert::false(
            $this->browsingCommunitiesPage->getCommunityCard($name)->hasViewButton()
        );
    }

    /**
     * @Given I try to join the :name community
     */
    public function iTryToJoinTheCommunity(string $name) : void
    {
        $this->browsingCommunitiesPage->joinCommunity($name);
    }

    /**
     * @Then I should be notified that I have joined the community
     */
    public function iShouldBeNotifiedThatIHaveJoinedTheCommunity() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Successfully joined the community', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    /**
     * @Then those communities should be :first and :second
     * @Then I should see the :name community
     */
    public function thoseCommunitiesShouldBeAnd(string ...$names) : void
    {
        $found = $this->browsingCommunitiesPage->getDisplayedCommunityNames();

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

        $found = $this->browsingCommunitiesPage->getCommunityCard($community)->getDescription();

        Assert::same($found, $description);
    }

    /**
     * @Then I should see that it is created by me
     */
    public function iShouldSeeThatItIsCreatedByMe() : void
    {
        $community = $this->storage->get('current_community_name');
        $logged = $this->storage->get('logged_user')->getName();

        $found = $this->browsingCommunitiesPage->getCommunityCard($community)->getAuthorName();

        Assert::same($found, $logged);
    }

    /**
     * @Then I should see that the :name community is created by :author
     */
    public function iShouldSeeThatTheCommunityIsCreatedBy(string $name, string $author) : void
    {
        $found = $this->browsingCommunitiesPage->getCommunityCard($name)->getAuthorName();

        Assert::same($found, $author);
    }

    /**
     * @Then I should see a message that there aren't any existing communities
     */
    public function iShouldSeeAMessageThatThereArenTAnyExistingCommunities() : void
    {
        Assert::true(
            $this->browsingCommunitiesPage->hasNoCommunitiesMessage()
        );
    }
}
