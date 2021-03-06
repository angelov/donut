<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\FeatureContexts;

use Angelov\Donut\Behat\Pages\Communities\CommunityPreviewPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Communities\Community;
use Behat\Behat\Context\Context;
use Angelov\Donut\Users\User;
use Webmozart\Assert\Assert;

class ViewingCommunitiesContext implements Context
{
    private $storage;
    private $communityPreviewPage;
    private $alertsChecker;

    public function __construct(StorageInterface $storage, CommunityPreviewPage $communityPreviewPage, AlertsCheckerInterface $alertsChecker)
    {
        $this->storage = $storage;
        $this->communityPreviewPage = $communityPreviewPage;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When I want to view the :name community
     * @When I am viewing the :name community
     */
    public function iWantToViewTheCommunity(string $name) : void
    {
        /** @var Community $community */
        $community = $this->storage->get('community_' . $name);

        $this->communityPreviewPage->open(['id' => $community->getId()]);

        $this->storage->set('current_community', $community);
    }

    /**
     * @When I want to view it
     * @When I am viewing it
     */
    public function iWantToViewIt() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('created_community');

        $this->communityPreviewPage->open(['id' => $community->getId()]);

        $this->storage->set('current_community', $community);
    }

    /**
     * @Then I should see its description
     */
    public function iShouldSeeItsDescription() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');

        $description = $this->communityPreviewPage->getDescription();

        Assert::same($community->getDescription(), $description, 'The displayed community description is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see its author
     */
    public function iShouldSeeItsAuthor() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $author = $this->communityPreviewPage->getAuthorName();

        Assert::same($author, $community->getAuthor()->getName(), 'The displayed community author is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see its creation date
     */
    public function iShouldSeeItsCreationDate() : void
    {
        /** @var Community $community */
        $community = $this->storage->get('current_community');
        $creationDate = $this->communityPreviewPage->getCreationDate();

        $expected = $community->getCreatedAt()->format('Y-m-d');

        Assert::same($creationDate, $expected, 'The displayed community creation date is not correct. Got [%s] instead of [%s]');
    }

    /**
     * @Then I should see a list of its members
     */
    public function iShouldSeeAListOfItsMembers() : void
    {
        Assert::true($this->communityPreviewPage->hasMembersList());
    }

    /**
     * @Then I shouldn't see a list of its members
     */
    public function iShouldnTSeeAListOfItsMembers() : void
    {
        Assert::false($this->communityPreviewPage->hasMembersList());
    }

    /**
     * @Then I should be part of it
     */
    public function iShouldBePartOfIt() : void
    {
        /** @var User $user */
        $user = $this->storage->get('logged_user');
        $list = $this->communityPreviewPage->getDisplayedMembers();

        Assert::true(
            in_array($user->getName(), $list, true)
        );
    }

    /**
     * @Then it should have :count member(s)
     */
    public function itShouldHaveMember(int $count) : void
    {
        Assert::eq($this->communityPreviewPage->countDisplayedMembers(), $count);
    }

    /**
     * @When I try to join it
     */
    public function iTryToJoinIt() : void
    {
        $this->communityPreviewPage->join();
    }

    /**
     * @Then I should be notified that I have joined it
     */
    public function iShouldBeNotifiedThatIHaveJoinedIt() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Successfully joined the community', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }

    /**
     * @When I try to leave it
     */
    public function iTryToLeaveIt() : void
    {
        $this->communityPreviewPage->leave();
    }

    /**
     * @Then I should be notified that I have left it
     */
    public function iShouldBeNotifiedThatIHaveLeftIt() : void
    {
        Assert::true(
            $this->alertsChecker->hasAlert('Successfully left the community', AlertsCheckerInterface::TYPE_SUCCESS)
        );
    }
}
