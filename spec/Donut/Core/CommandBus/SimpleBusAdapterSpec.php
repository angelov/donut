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

namespace spec\Angelov\Donut\Core\CommandBus;

use SimpleBus\Message\Bus\MessageBus;
use Angelov\Donut\Core\CommandBus\CommandBusInterface;
use Angelov\Donut\Core\CommandBus\SimpleBusAdapter;
use PhpSpec\ObjectBehavior;

class SimpleBusAdapterSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusAdapter::class);
    }

    function it_is_a_command_bus()
    {
        $this->shouldImplement(CommandBusInterface::class);
    }

    function it_passes_commands_to_simplebus($command, MessageBus $messageBus)
    {
        $messageBus->handle($command)->shouldBeCalled();

        $this->handle($command);

    }
}
