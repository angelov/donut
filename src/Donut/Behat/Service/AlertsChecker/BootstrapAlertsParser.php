<?php

namespace Angelov\Donut\Behat\Service\AlertsChecker;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;

// @todo write phpunit test
class BootstrapAlertsParser implements AlertsParserInterface
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getMessage(): string
    {
        return trim($this->findAlertElement()->getText());
    }

    public function getType(): string
    {
        $alert = $this->findAlertElement();

        $classes = $alert->getAttribute('class');

        if (!$classes) {
            throw new CouldNotDetermineAlertTypeException();
        }

        $classes = explode(' ', $classes);

        $types = [
            'alert-success' => AlertsCheckerInterface::TYPE_SUCCESS,
            'alert-danger' => AlertsCheckerInterface::TYPE_ERROR,
            'alert-warning' => AlertsCheckerInterface::TYPE_WARNING
        ];

        foreach ($classes as $class) {
            if (array_key_exists($class, $types)) {
                return $types[$class];
            }
        }

        throw new CouldNotDetermineAlertTypeException();
    }

    private function findAlertElement() : NodeElement
    {
        $page = $this->session->getPage();
        $alert = $page->find('css', '.alert');

        if (!$alert) {
            throw new AlertNotFoundException();
        }

        return $alert;
    }
}
