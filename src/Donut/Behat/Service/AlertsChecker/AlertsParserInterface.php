<?php

namespace Angelov\Donut\Behat\Service\AlertsChecker;

interface AlertsParserInterface
{
    /**
     * @throws AlertNotFoundException
     */
    public function getMessage() : string;

    /**
     * @throws AlertNotFoundException
     * @throws CouldNotDetermineAlertTypeException
     */
    public function getType() : string;
}
