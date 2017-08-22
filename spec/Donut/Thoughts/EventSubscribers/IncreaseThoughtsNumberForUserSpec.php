<?php

namespace spec\Angelov\Donut\Thoughts\EventSubscribers;

use Angelov\Donut\Thoughts\Events\ThoughtWasPublishedEvent;
use Angelov\Donut\Thoughts\EventSubscribers\IncreaseThoughtsNumberForUser;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Angelov\Donut\Users\User;

class IncreaseThoughtsNumberForUserSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $counter)
    {
        $this->beConstructedWith($counter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IncreaseThoughtsNumberForUser::class);
    }

    function it_handles_thought_was_published_events(
        ThoughtsCounterInterface $counter,
        ThoughtWasPublishedEvent $event,
        Thought $thought,
        User $user
    ) {
        $event->getThought()->willReturn($thought);
        $thought->getAuthor()->willReturn($user);

        $counter->increase($user)->shouldBeCalled();

        $this->notify($event);
    }
}
