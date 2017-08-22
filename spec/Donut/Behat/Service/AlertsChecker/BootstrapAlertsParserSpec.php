<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace spec\Angelov\Donut\Behat\Service\AlertsChecker;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Prophecy\Argument;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertNotFoundException;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsCheckerInterface;
use Angelov\Donut\Behat\Service\AlertsChecker\AlertsParserInterface;
use Angelov\Donut\Behat\Service\AlertsChecker\BootstrapAlertsParser;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Behat\Service\AlertsChecker\CouldNotDetermineAlertTypeException;

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
