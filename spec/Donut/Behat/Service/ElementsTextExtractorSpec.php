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

namespace spec\Angelov\Donut\Behat\Service;

use Behat\Mink\Element\NodeElement;
use Angelov\Donut\Behat\Service\ElementsTextExtractor;
use PhpSpec\ObjectBehavior;

class ElementsTextExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ElementsTextExtractor::class);
    }

    function it_extracts_text_from_multiple_elements(NodeElement $first, NodeElement $second)
    {
        $first->getText()->shouldBeCalled()->willReturn('first');
        $second->getText()->shouldBeCalled()->willReturn('second');

        $this::fromElements([$first, $second])->shouldReturn(['first', 'second']);
    }

    function it_throws_exception_if_some_element_is_of_wrong_type(NodeElement $first)
    {
        $first->getText()->willReturn('this is okay');

        $this->shouldThrow(\InvalidArgumentException::class)->during('fromElements', [[$first, 'not an element']]);
    }
}
