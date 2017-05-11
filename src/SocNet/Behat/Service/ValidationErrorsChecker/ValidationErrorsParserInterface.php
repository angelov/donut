<?php

namespace SocNet\Behat\Service\ValidationErrorsChecker;

interface ValidationErrorsParserInterface
{
    /**
     * @return string[]
     */
    public function getMessages() : array;
}
