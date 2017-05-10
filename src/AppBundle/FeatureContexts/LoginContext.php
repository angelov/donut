<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use SocNet\Behat\Pages\Users\LoginPage;
use SocNet\Behat\Pages\Users\RegistrationPage;
use Webmozart\Assert\Assert;

class LoginContext implements Context
{
    private $session;
    private $loginPage;
    private $registrationPage;

    public function __construct(LoginPage $loginPage, RegistrationPage $registrationPage, Session $session)
    {
        $this->session = $session;
        $this->loginPage = $loginPage;
        $this->registrationPage = $registrationPage;
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
        Assert::true($this->session->getPage()->hasContent('Invalid credentials.'));
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
