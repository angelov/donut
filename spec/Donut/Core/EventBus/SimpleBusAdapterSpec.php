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

namespace spec\Angelov\Donut\Core\EventBus;

use SimpleBus\Message\Bus\MessageBus;
use Angelov\Donut\Core\EventBus\EventBusInterface;
use Angelov\Donut\Core\EventBus\SimpleBusAdapter;
use PhpSpec\ObjectBehavior;

class SimpleBusAdapterSpec extends ObjectBehavior
{
    public function let(MessageBus $simpleBus)
    {
        $this->beConstructedWith($simpleBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusAdapter::class);
    }

    function it_is_event_bus()
    {
        $this->shouldImplement(EventBusInterface::class);
    }

    function it_passes_fired_events_to_simplebus($event, MessageBus $simpleBus)
    {
        $simpleBus->handle($event)->shouldBeCalled();

        $this->fire($event);
    }
}
