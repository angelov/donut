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

namespace spec\Angelov\Donut\Behat\Service\ValidationErrorsChecker;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsParserInterface;

class ValidationErrorsParserSpec extends ObjectBehavior
{
    function let(Session $session, DocumentElement $page)
    {
        $this->beConstructedWith($session);

        $session->getPage()->willReturn($page);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ValidationErrorsParser::class);
    }

    function it_is_validation_errors_parser()
    {
        $this->shouldImplement(ValidationErrorsParserInterface::class);
    }

    function it_returns_empty_array_when_there_are_no_errors(DocumentElement $page)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([]);

        $this->getMessages()->shouldReturn([]);
    }

    function it_returns_array_when_there_is_one_error(DocumentElement $page, NodeElement $errorElement)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([$errorElement]);
        $errorElement->getText()->shouldBeCalled()->willReturn('Please enter your name');

        $this->getMessages()->shouldReturn(['Please enter your name']);
    }

    function it_returns_all_messages_when_there_are_multiple_errors(DocumentElement $page, NodeElement $firstError, NodeElement $secondError)
    {
        $page->findAll('css', Argument::type('string'))->shouldBeCalled()->willReturn([$firstError, $secondError]);
        $firstError->getText()->shouldBeCalled()->willReturn('Please enter your name');
        $secondError->getText()->shouldBeCalled()->willReturn('Please enter your e-mail address');

        $this->getMessages()->shouldReturn(['Please enter your name', 'Please enter your e-mail address']);
    }
}
