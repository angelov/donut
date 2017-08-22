<?php

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
