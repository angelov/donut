<?php

namespace spec\SocNet\Behat\Service\AlertsChecker;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Prophecy\Argument;
use SocNet\Behat\Service\AlertsChecker\AlertNotFoundException;
use SocNet\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use SocNet\Behat\Service\AlertsChecker\AlertsParserInterface;
use SocNet\Behat\Service\AlertsChecker\BootstrapAlertsParser;
use PhpSpec\ObjectBehavior;
use SocNet\Behat\Service\AlertsChecker\CouldNotDetermineAlertTypeException;

class BootstrapAlertsParserSpec extends ObjectBehavior
{
    function let(Session $session, DocumentElement $page)
    {
        $this->beConstructedWith($session);

        $session->getPage()->willReturn($page);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BootstrapAlertsParser::class);
    }

    function it_is_alerts_parser()
    {
        $this->shouldImplement(AlertsParserInterface::class);
    }

    function it_returns_alert_message(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn($alertElement);
        $alertElement->getText()->willReturn('Message sent.');

        $this->getMessage()->shouldReturn('Message sent.');
    }

    function it_throws_exception_when_requesting_message_if_no_alert_is_found(DocumentElement $page)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(AlertNotFoundException::class)->during('getMessage');
    }

    function it_parses_success_alerts(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn($alertElement);
        $alertElement->getAttribute('class')->willReturn('alert alert-success');

        $this->getType()->shouldReturn(AlertsCheckerInterface::TYPE_SUCCESS);
    }

    function it_parses_warning_alerts(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn($alertElement);
        $alertElement->getAttribute('class')->willReturn('alert alert-warning');

        $this->getType()->shouldReturn(AlertsCheckerInterface::TYPE_WARNING);
    }

    function it_parses_error_alerts(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn($alertElement);
        $alertElement->getAttribute('class')->willReturn('alert alert-danger');

        $this->getType()->shouldReturn(AlertsCheckerInterface::TYPE_ERROR);
    }

    function it_throws_exception_for_unknown_types(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn($alertElement);
        $alertElement->getAttribute('class')->willReturn('');

        $this->shouldThrow(CouldNotDetermineAlertTypeException::class)->during('getType');
    }

    function it_throws_exception_when_requesting_type_if_no_alert_is_found(DocumentElement $page, NodeElement $alertElement)
    {
        $page->find('css', Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(AlertNotFoundException::class)->during('getType');
    }
}
