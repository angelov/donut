<?php

namespace SocNet\Behat\Pages;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;

abstract class Page implements PageInterface
{
    private $session;
    private $router;

    public function __construct(Session $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    public function open(array $attributes = []) : void
    {
        $url = $this->generateUrl($attributes);

        $this->session->getDriver()->visit($url);
    }

    public function isOpen(array $attributes = []): bool
    {
        $url = $this->generateUrl($attributes);
        $currentUrl = $this->getCurrentUrl();

        return ($currentUrl === $url) && $this->checkStatusCode(200);
    }

    private function generateUrl(array $attributes = []) : string
    {
        return $this->router->generate($this->getRoute(), $attributes, UrlGenerator::ABSOLUTE_URL);
    }

    private function getCurrentUrl() : string
    {
        return $this->getSession()->getCurrentUrl();
    }

    private function checkStatusCode(int $statusCode) : bool
    {
        return $this->getSession()->getStatusCode() === $statusCode;
    }

    protected function getSession() : Session
    {
        return $this->session;
    }

    protected function getDocument() : DocumentElement
    {
        return $this->getSession()->getPage();
    }

    abstract protected function getRoute() : string;
}
