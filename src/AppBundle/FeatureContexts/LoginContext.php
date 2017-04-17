<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class LoginContext implements Context
{
    private $session;
    private $router;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @When I want to log in
     */
    public function iWantToLogIn() : void
    {
        $url = $this->router->generate('security_login');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @When I specify the email as :email
     * @When I don't specify the email
     */
    public function iSpecifyTheEmailAs(string $email = '') : void
    {
        $this->session->getPage()->fillField('Username', $email); // @todo change label to E-mail
    }

    /**
     * @When I specify the password as :password
     * @When I don't specify the password
     */
    public function iSpecifyThePasswordAs(string $password = '') : void
    {
        $this->session->getPage()->fillField('Password', $password);
    }

    /**
     * @When I try to log in
     */
    public function iTryToLogIn() : void
    {
        $this->session->getPage()->pressButton('Login');
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn() : void
    {
        $homepage = $this->router->generate('app.thoughts.index', [], UrlGenerator::ABSOLUTE_URL);
        $currentUrl = $this->session->getCurrentUrl();

        Assert::same($currentUrl, $homepage);
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
        $url = $this->router->generate('security_login', [], UrlGenerator::ABSOLUTE_URL);
        $currentUrl = $this->session->getDriver()->getCurrentUrl();

        Assert::same($url, $currentUrl, 'Expected to be on the login page.');
    }
}
