<?php

namespace spec\Angelov\Donut\Behat\Service\AlertsChecker;

use Angelov\Donut\Behat\Service\AlertsChecker\AlertNotFoundException;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsChecker;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsParserInterface;
use Angelov\Donut\Behat\Service\AlertsChecker\CouldNotDetermineAlertTypeException;

class AlertsCheckerSpec extends ObjectBehavior
{
    function let(AlertsParserInterface $alertsParser)
    {
        $this->beConstructedWith($alertsParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AlertsChecker::class);
    }

    function it_is_alerts_checker()
    {
        $this->shouldImplement(AlertsCheckerInterface::class);
    }

    function it_cheks_if_success_alert_is_shown(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getType()->shouldBeCalled()->willReturn(AlertsCheckerInterface::TYPE_SUCCESS);
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('successful alert');

        $this->hasAlert('successful alert', AlertsCheckerInterface::TYPE_SUCCESS)->shouldReturn(true);
    }

    function it_checks_if_warning_alert_is_shown(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getType()->shouldBeCalled()->willReturn(AlertsCheckerInterface::TYPE_WARNING);
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('warning alert');

        $this->hasAlert('warning alert', AlertsCheckerInterface::TYPE_WARNING)->shouldReturn(true);
    }

    function it_checks_if_error_alert_is_shown(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getType()->shouldBeCalled()->willReturn(AlertsCheckerInterface::TYPE_ERROR);
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('error alert');

        $this->hasAlert('error alert', AlertsCheckerInterface::TYPE_ERROR)->shouldReturn(true);
    }

    function it_returns_false_if_the_message_is_wrong(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('error alert');

        $this->hasAlert('warning alert', AlertsCheckerInterface::TYPE_ERROR)->shouldReturn(false);
    }

    function it_returns_false_if_the_type_is_wrong(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getType()->shouldBeCalled()->willReturn(AlertsCheckerInterface::TYPE_ERROR);
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('error alert');

        $this->hasAlert('error alert', AlertsCheckerInterface::TYPE_WARNING)->shouldReturn(false);
    }

    function it_returns_false_if_no_alert_is_shown(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getMessage()->willThrow(AlertNotFoundException::class);

        $this->hasAlert('error alert', AlertsCheckerInterface::TYPE_WARNING)->shouldReturn(false);
    }

    function it_returns_false_if_the_type_is_not_resolved(AlertsParserInterface $alertsParser)
    {
        $alertsParser->getMessage()->shouldBeCalled()->willReturn('error alert');
        $alertsParser->getType()->willThrow(CouldNotDetermineAlertTypeException::class);

        $this->hasAlert('error alert', 'wrong type')->shouldReturn(false);
    }
}
