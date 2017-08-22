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

use Angelov\Donut\Behat\Service\ValidationErrorsChecker\GlobalValidationErrorsChecker;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsCheckerInterface;
use Angelov\Donut\Behat\Service\ValidationErrorsChecker\ValidationErrorsParserInterface;

class GlobalValidationErrorsCheckerSpec extends ObjectBehavior
{
    const FIELD = 'email_address';

    function let(ValidationErrorsParserInterface $errorsParser)
    {
        $this->beConstructedWith($errorsParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GlobalValidationErrorsChecker::class);
    }

    function it_is_validation_errors_checker()
    {
        $this->shouldImplement(ValidationErrorsCheckerInterface::class);
    }

    function it_returns_true_when_the_message_is_found(ValidationErrorsParserInterface $errorsParser)
    {
        $errorsParser->getMessages()->willReturn(['msg1', 'msg2']);

        $this->checkMessageForField(self::FIELD, 'msg2')->shouldReturn(true);
    }

    function it_returns_false_when_the_message_is_not_found(ValidationErrorsParserInterface $errorsParser)
    {
        $errorsParser->getMessages()->willReturn([]);

        $this->checkMessageForField(self::FIELD, 'msg')->shouldReturn(false);
    }
}
