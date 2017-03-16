<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;

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
    public function iWantToLogIn()
    {
        $url = $this->router->generate('security_login');
        $this->session->getDriver()->visit($url);
    }

    /**
     * @When I specify the email as :email
     */
    public function iSpecifyTheEmailAs(string $email)
    {
        $this->session->getPage()->fillField('Username', $email); // @todo change label to E-mail
    }

    /**
     * @When I specify the password as :password
     */
    public function iSpecifyThePasswordAs(string $password)
    {
        $this->session->getPage()->fillField('Password', $password);
    }

    /**
     * @When I try to log in
     */
    public function iTryToLogIn()
    {
        $this->session->getPage()->pressButton('Login');
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn()
    {
        $homepage = $this->router->generate('app.thoughts.index', [], UrlGenerator::ABSOLUTE_URL);

        if ($this->session->getCurrentUrl() !== $homepage) {
            throw new \Exception();
        }
    }
}
