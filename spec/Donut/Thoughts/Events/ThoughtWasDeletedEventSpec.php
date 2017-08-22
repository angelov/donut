<?php

namespace spec\Angelov\Donut\Thoughts\Events;

use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Angelov\Donut\Thoughts\Thought;

class ThoughtWasDeletedEventSpec extends ObjectBehavior
{
    public function let(Thought $thought)
    {
        $this->beConstructedWith($thought);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ThoughtWasDeletedEvent::class);
    }

    function it_holds_the_thought(Thought $thought)
    {
        $this->getThought()->shouldReturn($thought);
    }
}
