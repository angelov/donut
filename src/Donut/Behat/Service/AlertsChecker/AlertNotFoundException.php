<?php

namespace Angelov\Donut\Behat\Service\AlertsChecker;

use RuntimeException;

class AlertNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Could not find any alert on the page.');
    }
}
