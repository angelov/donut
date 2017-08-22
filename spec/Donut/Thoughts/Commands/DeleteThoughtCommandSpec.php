<?php

namespace spec\Angelov\Donut\Thoughts\Commands;

use Angelov\Donut\Thoughts\Commands\DeleteThoughtCommand;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Thought;

class DeleteThoughtCommandSpec extends ObjectBehavior
{
    public function let(Thought $thought)
    {
        $this->beConstructedWith($thought);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteThoughtCommand::class);
    }

    function it_holds_the_thought(Thought $thought)
    {
        $this->getThought()->shouldReturn($thought);
    }
}
