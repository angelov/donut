<?php

namespace SocNet\Behat\Service\AlertsChecker;

class AlertsChecker implements AlertsCheckerInterface
{
    private $alertsParser;

    public function __construct(AlertsParserInterface $alertsParser)
    {
        $this->alertsParser = $alertsParser;
    }

    public function hasAlert(string $message, string $type): bool
    {
        try {
            return $message === $this->alertsParser->getMessage() && $type === $this->alertsParser->getType();
        } catch(AlertNotFoundException | CouldNotDetermineAlertTypeException $e) {
            return false;
        }
    }
}
