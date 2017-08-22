<?php

namespace Angelov\Donut\Behat\Service\ValidationErrorsChecker;

interface ValidationErrorsCheckerInterface
{
    public function checkMessageForField(string $field, string $message) : bool;
}
