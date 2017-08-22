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

namespace Angelov\Donut\Behat\Pages;

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
