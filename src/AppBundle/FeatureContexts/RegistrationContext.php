<?php

namespace AppBundle\FeatureContexts;

use Behat\Mink\Session;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class RegistrationContext extends RawMinkContext
{
    private $router;
    private $session;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Given /^I want to create a new user account$/
     */
    public function iWantToCreateANewUserAccount()
    {
        $url = $this->router->generate('app.users.register');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @Given I specify the name as :name
     */
    public function iSpecifyTheNameAs(string $name)
    {
        $this->session->getPage()->fillField('Name', $name);
    }

    /**
     * @Given I specify the email as :email
     */
    public function iSpecifyTheEmailAs(string $email)
    {
        $this->session->getPage()->fillField('Email', $email);
    }

    /**
     * @Given I specify the password as :password
     */
    public function iSpecifyThePasswordAs(string $password)
    {
        $this->session->getPage()->fillField('Password', $password);
        $this->session->getPage()->fillField('Repeat Password', $password);
    }

    /**
     * @Given I create the account
     */
    public function iCreateTheAccount()
    {
        $this->session->getPage()->pressButton('Register');
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
        $url = $this->router->generate('security_login', [], UrlGenerator::ABSOLUTE_URL);
        $currentUrl = $this->session->getDriver()->getCurrentUrl();

        if ($currentUrl !== $url) {
            throw new \RuntimeException(sprintf(
                'Expected to be on [%s], but ended up on [%s] instead.',
                $url,
                $currentUrl
            ));
        }
    }
}
