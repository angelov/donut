<?php

namespace SocNet\Users\EmailAvailabilityChecker;

interface EmailAvailabilityCheckerInterface
{
    public function isTaken(string $email) : bool;
}
