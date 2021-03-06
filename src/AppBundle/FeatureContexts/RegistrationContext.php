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

use Behat\MinkExtension\Context\RawMinkContext;
use Angelov\Donut\Behat\Pages\Users\LoginPage;
use Angelov\Donut\Behat\Pages\Users\RegistrationPage;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\Storage\StorageInterface;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
use Webmozart\Assert\Assert;

class RegistrationContext extends RawMinkContext
{
    private $storage;
    private $registrationPage;
    private $loginPage;
    private $validationErrorsChecker;
    private $alertsChecker;

    public function __construct(
        RegistrationPage $registrationPage,
        LoginPage $loginPage,
        StorageInterface $storage,
        ValidationErrorsCheckerInterface $validationErrorsChecker,
        AlertsCheckerInterface $alertsChecker
    ) {
        $this->storage = $storage;
        $this->registrationPage = $registrationPage;
        $this->loginPage = $loginPage;
        $this->validationErrorsChecker = $validationErrorsChecker;
        $this->alertsChecker = $alertsChecker;
    }

    /**
     * @When /^I want to create a new user account$/
     */
    public function iWantToCreateANewUserAccount() : void
    {
        $this->registrationPage->open();
    }

    /**
     * @When I specify the name as :name
     * @When I don't specify the name
     */
    public function iSpecifyTheNameAs(string $name = '') : void
    {
        $this->registrationPage->specifyName($name);
    }

    /**
     * @When I specify the email as :email
     * @When I don't specify the email
     */
    public function iSpecifyTheEmailAs(string $email = '') : void
    {
        $this->registrationPage->specifyEmail($email);
    }

    /**
     * @When I specify the password as :password
     * @When I don't specify the password
     */
    public function iSpecifyThePasswordAs(string $password = '') : void
    {
        $this->registrationPage->specifyPassword($password);

        $this->storage->set('password', $password);
    }

    /**
     * @When I confirm the password
     */
    public function iConfirmThePassword() : void
    {
        $password = $this->storage->get('password');

        $this->registrationPage->confirmPassword($password);
    }

    /**
     * @When I don't confirm the password
     */
    public function iDonTConfirmThePassword() : void
    {
        $this->registrationPage->confirmPassword('');
    }

    /**
     * @When I (try to) create the account
     */
    public function iCreateTheAccount() : void
    {
        $this->registrationPage->register();
    }

    /**
     * @Then I should be notified that my user account has been successfully created
     */
    public function iShouldBeNotifiedThatMyUserAccountHasBeenSuccessfullyCreated() : void
    {
        Assert::true($this->alertsChecker->hasAlert('Registration was successful. You many now login.', AlertsCheckerInterface::TYPE_SUCCESS));
    }

    /**
     * @Then I should not be logged in
     */
    public function iShouldNotBeLoggedIn() : void
    {
        Assert::true(
            $this->registrationPage->isOpen() || $this->loginPage->isOpen()
        );
    }

    /**
     * @Then I should be notified that the :field is required
     */
    public function iShouldBeNotifiedThatAFieldIsRequired(string $field) : void
    {
        $message = sprintf('Please enter your %s.', $field);

        $this->assertValidationError($field, $message);
    }

    /**
     * @Then I should be notified that the password must be confirmed
     */
    public function iShouldBeNotifiedThatThePasswordMustBeConfirmed() : void
    {
        $this->assertValidationError('password', 'Please confirm your password.');
    }

    /**
     * @Then I should be notified that the specified email is already in use
     */
    public function iShouldBeNotifiedThatTheSpecifiedEmailIsAlreadyInUse() : void
    {
        $this->assertValidationError('email', 'The email is already in use.');
    }

    /**
     * @Then I should be notified that the password is too short
     */
    public function iShouldBeNotifiedThatThePasswordIsTooShort() : void
    {
        $this->assertValidationError('password', 'The password must be at least 6 characters long.');
    }

    /**
     * @When I specify my city as :city
     */
    public function iSpecifyMyCityAsSkopje(string $city) : void
    {
        $this->registrationPage->chooseCity($city);
    }

    /**
     * @When I don't specify my city
     */
    public function iDonTSpecifyMyCity() : void
    {
        $this->registrationPage->chooseCity('');
    }

    private function assertValidationError(string $field, string $message) : void
    {
        Assert::true(
            $this->validationErrorsChecker->checkMessageForField($field, $message),
            sprintf('Could not find the "%s" validation message for the "%s" field', $message, $field)
        );
    }
}
