<?php

namespace Angelov\Donut\Users\EmailAvailabilityChecker;

interface EmailAvailabilityCheckerInterface
{
    public function isTaken(string $email) : bool;
}
