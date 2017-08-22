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

use Behat\Behat\Context\Context;
use Angelov\Donut\Behat\Pages\Communities\CreateCommunityPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
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
