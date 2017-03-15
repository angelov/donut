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
    private $storage;

    public function __construct(Session $session, RouterInterface $router, Storage $storage)
    {
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When /^I want to create a new user account$/
     */
    public function iWantToCreateANewUserAccount()
    {
        $url = $this->router->generate('app.users.register');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @When I specify the name as :name
     */
    public function iSpecifyTheNameAs(string $name)
    {
        $this->session->getPage()->fillField('Name', $name);
    }

    /**
     * @When I don't specify the name
     */
    public function iDonTSpecifyTheName()
    {
        $this->session->getPage()->fillField('Name', '');
    }

    /**
     * @When I specify the email as :email
     */
    public function iSpecifyTheEmailAs(string $email)
    {
        $this->session->getPage()->fillField('Email', $email);
    }

    /**
     * @When I don't specify the email
     */
    public function iDonTSpecifyTheEmail()
    {
        $this->session->getPage()->fillField('Email', '');
    }

    /**
     * @When I specify the password as :password
     */
    public function iSpecifyThePasswordAs(string $password)
    {
        $this->session->getPage()->fillField('Password', $password);
        $this->session->getPage()->fillField('Repeat Password', $password);
        $this->storage->set('password', $password);
    }

    /**
     * @When I don't specify the password
     */
    public function iDonTSpecifyThePassword()
    {
        $this->session->getPage()->fillField('Password', '');
    }

    /**
     * @When I confirm the password
     */
    public function iConfirmThePassword()
    {
        $password = $this->storage->get('password');
        $this->session->getPage()->fillField('Repeat Password', $password);
    }

    /**
     * @When I don't confirm the password
     */
    public function iDonTConfirmThePassword()
    {
        $this->session->getPage()->fillField('Repeat Password', '');
    }

    /**
     * @When I (try to) create the account
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
        $found = $this->session->getPage()->hasContent('Registration was successful. You many now login.');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should not be logged in
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

    /**
     * @Then I should be notified that the :field is required
     */
    public function iShouldBeNotifiedThatAFieldIsRequired(string $field)
    {
        $found = $this->session->getPage()->hasContent(sprintf(
            'Please enter your %s.',
            $field
        ));

        if (!$found) {
            throw new \RuntimeException('Could not find the proper validation message.');
        }
    }

    /**
     * @Then I should be notified that the password must be confirmed
     */
    public function iShouldBeNotifiedThatThePasswordMustBeConfirmed()
    {
        $found = $this->session->getPage()->hasContent('Please confirm your password.');

        if (!$found) {
            throw new \RuntimeException('Could not find the proper validation message.');
        }
    }

    /**
     * @Then I should be notified that the specified email is already in use
     */
    public function iShouldBeNotifiedThatTheSpecifiedEmailIsAlreadyInUse()
    {
        $found = $this->session->getPage()->hasContent('The email is already in use.');

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I should be notified that the password is too short
     */
    public function iShouldBeNotifiedThatThePasswordIsTooShort()
    {
        $found = $this->session->getPage()->hasContent('The password must be at least 6 characters long.');

        if (!$found) {
            throw new \Exception();
        }
    }
}
