<?php

namespace Angelov\Donut\Behat\Service\ValidationErrorsChecker;

interface ValidationErrorsParserInterface
{
    /**
     * @return string[]
     */
    public function getMessages() : array;
}
