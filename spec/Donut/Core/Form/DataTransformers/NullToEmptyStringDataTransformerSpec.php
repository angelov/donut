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

namespace spec\Angelov\Donut\Core\Form\DataTransformers;

use Angelov\Donut\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\DataTransformerInterface;

class NullToEmptyStringDataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullToEmptyStringDataTransformer::class);
    }

    function it_is_data_transformer()
    {
        $this->shouldBeAnInstanceOf(DataTransformerInterface::class);
    }

    function it_keeps_original_representation_untrasformed()
    {
        $this->transform('example')->shouldReturn('example');
        $this->transform(null)->shouldReturn(null);
    }

    function it_transforms_null_to_empty_string_when_doing_reverse_transformation()
    {
        $this->reverseTransform(null)->shouldReturn('');
    }

    function it_keeps_original_non_nullable_representation_when_doing_reverse_transformation()
    {
        $this->reverseTransform('something')->shouldReturn('something');
    }
}
