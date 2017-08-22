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
use Angelov\Donut\Behat\Pages\Users\LoginPage;
use Angelov\Donut\Behat\Pages\Users\RegistrationPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Webmozart\Assert\Assert;

class LoginContext implements Context
{
    private $loginPage;
    private $registrationPage;
    private $alertsChecker;

    public function __construct(LoginPage $loginPage, RegistrationPage $registrationPage, AlertsCheckerInterface $alertsChecker)
    {
        $this->loginPage = $loginPage;
        $this->registrationPage = $registrationPage;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When I want to log in
     */
    public function iWantToLogIn() : void
    {
        $this->loginPage->open();
    }

    /**
     * @When I specify the email as :email
     * @When I don't specify the email
     */
    public function iSpecifyTheEmailAs(string $email = '') : void
    {
        $this->loginPage->specifyEmail($email);
    }

    /**
     * @When I specify the password as :password
     * @When I don't specify the password
     */
    public function iSpecifyThePasswordAs(string $password = '') : void
    {
        $this->loginPage->specifyPassword($password);
    }

    /**
     * @When I try to log in
     */
    public function iTryToLogIn() : void
    {
        $this->loginPage->login();
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn() : void
    {
        // @todo not safe, refactor
        Assert::false(
            $this->registrationPage->isOpen() || $this->loginPage->isOpen()
        );
    }

    /**
     * @Then I should be notified about bad credentials
     */
    public function iShouldBeNotifiedAboutBadCredentials() : void
    {
        Assert::true($this->alertsChecker->hasAlert('Invalid credentials.', AlertsCheckerInterface::TYPE_ERROR));
    }

    /**
     * @Then I should not be logged in
     */
    public function iShouldNotBeLoggedIn() : void
    {
        // @todo refactor
        Assert::true(
            $this->registrationPage->isOpen() || $this->loginPage->isOpen()
        );
    }
}
