<?php

namespace SocNet\Behat\Service\ValidationErrorsChecker;

interface ValidationErrorsCheckerInterface
{
    public function checkMessageForField(string $field, string $message) : bool;
}
