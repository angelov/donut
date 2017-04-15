<?php

namespace spec\SocNet\Thoughts\EventSubscribers;

use SocNet\Thoughts\Events\ThoughtWasPublishedEvent;
use SocNet\Thoughts\EventSubscribers\IncreaseThoughtsNumberForUser;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Thought;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use SocNet\Users\User;

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
