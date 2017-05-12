<?php

namespace SocNet\Behat\Service\AlertsChecker;

interface AlertsCheckerInterface
{
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';

    public function hasAlert(string $message, string $type) : bool;
}
