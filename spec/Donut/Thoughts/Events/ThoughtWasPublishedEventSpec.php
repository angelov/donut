<?php

namespace spec\Angelov\Donut\Thoughts\Events;

use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Thought;

class ThoughtWasPublishedEventSpec extends ObjectBehavior
{
    public function let(Thought $thought)
    {
        $this->beConstructedWith($thought);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ThoughtWasPublishedEvent::class);
    }

    function it_holds_the_thought(Thought $thought)
    {
        $this->getThought()->shouldReturn($thought);
    }
}
