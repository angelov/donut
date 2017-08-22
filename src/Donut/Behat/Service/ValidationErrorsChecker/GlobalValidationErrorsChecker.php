<?php

namespace Angelov\Donut\Behat\Service\ValidationErrorsChecker;

class GlobalValidationErrorsChecker implements ValidationErrorsCheckerInterface
{
    private $errorsParser;

    public function __construct(ValidationErrorsParserInterface $errorsParser)
    {
        $this->errorsParser = $errorsParser;
    }

    /**
     * Note: This checker ignores the field name and just checks if the message
     * is found somewhere on the page
     */
    public function checkMessageForField(string $field, string $message): bool
    {
        $errors = $this->errorsParser->getMessages();

        return in_array($message, $errors, true);
    }
}
