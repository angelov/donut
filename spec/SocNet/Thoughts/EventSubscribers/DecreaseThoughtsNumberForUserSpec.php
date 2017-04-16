<?php

namespace spec\SocNet\Thoughts\EventSubscribers;

use SocNet\Thoughts\Events\ThoughtWasDeletedEvent;
use SocNet\Thoughts\EventSubscribers\DecreaseThoughtsNumberForUser;
use PhpSpec\ObjectBehavior;
use SocNet\Thoughts\Thought;
use SocNet\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use SocNet\Users\User;

class DecreaseThoughtsNumberForUserSpec extends ObjectBehavior
{
    function let(ThoughtsCounterInterface $counter)
    {
        $this->beConstructedWith($counter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DecreaseThoughtsNumberForUser::class);
    }

    function it_handles_thought_was_deleted_events(ThoughtWasDeletedEvent $event, Thought $thought, User $user, ThoughtsCounterInterface $counter)
    {
        $event->getThought()->willReturn($thought);
        $thought->getAuthor()->willReturn($user);

        $counter->decrease($user)->shouldBeCalled();

        $this->notify($event);
    }
}
