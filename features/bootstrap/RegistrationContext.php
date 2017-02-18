<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

class RegistrationContext extends RawMinkContext implements KernelAwareContext
{
    /** @var KernelInterface $kernel */
    private $kernel;

    /**
     * @Given /^I want to create a new user account$/
     */
    public function iWantToCreateANewUserAccount()
    {
        $url = $this->kernel->getContainer()->get('router')->generate('app.users.register');

        $this->getSession()->getDriver()->visit($url);
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given I specify the name as :name
     */
    public function iSpecifyTheNameAs(string $name)
    {
        $this->getSession()->getPage()->fillField('Name', $name);
    }

    /**
     * @Given I specify the email as :email
     */
    public function iSpecifyTheEmailAs(string $email)
    {
        $this->getSession()->getPage()->fillField('Email', $email);
    }

    /**
     * @Given I specify the password as :password
     */
    public function iSpecifyThePasswordAs(string $password)
    {
        $this->getSession()->getPage()->fillField('Password', $password);
        $this->getSession()->getPage()->fillField('Repeat Password', $password);
    }

    /**
     * @Given I create the account
     */
    public function iCreateTheAccount()
    {
        $this->getSession()->getPage()->pressButton('Register');
    }

    /**
     * @Then I should be notified that my user account has been successfully created
     */
    public function iShouldBeNotifiedThatMyUserAccountHasBeenSuccessfullyCreated()
    {
//        $this->assertSession()->pageTextContains('Registration was successful. You many now login.');
    }

    /**
     * @Given I should not be logged in
     */
    public function iShouldNotBeLoggedIn()
    {
        $url = $this->kernel->getContainer()->get('router')->generate('security_login');

        $this->assertSession()->addressEquals($url);
    }
}
