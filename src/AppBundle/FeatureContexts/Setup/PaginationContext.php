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

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Webmozart\Assert\Assert;

class PaginationContext implements Context
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @When when I go to page :number
     */
    public function whenIGoToPage(int $number) : void
    {
        $this->session->getPage()->clickLink($number);
    }

    /**
     * @Then I should not see a pagination
     */
    public function iShouldNotSeeAPagination() : void
    {
        Assert::false($this->session->getPage()->has('css', '.pagination'));
    }

    /**
     * @Given I am on the first page
     */
    public function iAmOnTheFirstPage() : void
    {
        // @todo works for now, but find better solution
    }

    /**
     * @Given I am on the second page
     * @When when I go to the second page
     */
    public function iAmOnTheSecondPage() : void
    {
        // @todo works for now, but find better solution
        $this->session->getPage()->clickLink('Next page');
    }

    /**
     * @Then I should be able to go to next page
     */
    public function iShouldBeAbleToGoToNextPage() : void
    {
        Assert::true($this->session->getPage()->has('css', 'a:contains("Next page")'));
    }

    /**
     * @Then I should not be able to go to next page
     */
    public function iShouldNotBeAbleToGoToNextPage() : void
    {
        Assert::false($this->session->getPage()->has('css', 'a:contains("Next page")'));
    }

    /**
     * @Then I should not be able to go to previous page
     */
    public function iShouldNotBeAbleToGoToPreviousPage() : void
    {
        Assert::false($this->session->getPage()->has('css', 'a:contains("Previous page")'));
    }

    /**
     * @Then I should be able to go to previous page
     */
    public function iShouldBeAbleToGoToPreviousPage() : void
    {
        Assert::true($this->session->getPage()->has('css', 'a:contains("Previous page")'));
    }
}
