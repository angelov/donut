<?php

namespace spec\Angelov\Donut\Behat\Service\AlertsChecker;

use Angelov\Donut\Behat\Service\AlertsChecker\AlertNotFoundException;
use PhpSpec\ObjectBehavior;

class AlertNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AlertNotFoundException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }

    function it_has_message_by_default()
    {
        $this->getMessage()->shouldReturn('Could not find any alert on the page.');
    }
}
