<?php

namespace spec\SocNet\Behat\Service\AlertsChecker;

use SocNet\Behat\Service\AlertsChecker\CouldNotDetermineAlertTypeException;
use PhpSpec\ObjectBehavior;

class CouldNotDetermineAlertTypeExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CouldNotDetermineAlertTypeException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }
}
