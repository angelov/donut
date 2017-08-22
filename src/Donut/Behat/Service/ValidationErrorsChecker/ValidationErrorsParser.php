<?php

namespace Angelov\Donut\Behat\Service\ValidationErrorsChecker;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;

class ValidationErrorsParser implements ValidationErrorsParserInterface
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getMessages(): array
    {
        return $this->extractMessages(
            $this->findValidationErrorElements()
        );
    }

    /**
     * @return NodeElement[]
     */
    private function findValidationErrorElements() : array
    {
        return $this->session->getPage()->findAll('css', '.validation_error');
    }

    /**
     * @param NodeElement[] $elements
     * @return string[]
     */
    private function extractMessages(array $elements) : array
    {
        $extractor = function (NodeElement $element) : string {
            return $element->getText();
        };

        return array_map($extractor, $elements);
    }
}
