<?php

namespace AppBundle\FeatureContexts\Setup;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;

class PaginationContext implements Context
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @When when I go to page :number
     */
    public function whenIGoToPage(int $number)
    {
        $this->session->getPage()->clickLink($number);
    }
}
