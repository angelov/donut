<?php

namespace spec\Angelov\Donut\Thoughts\EventSubscribers;

use Angelov\Donut\Thoughts\Events\ThoughtWasDeletedEvent;
use Angelov\Donut\Thoughts\EventSubscribers\DecreaseThoughtsNumberForUser;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Thoughts\Thought;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;
use Angelov\Donut\Users\User;

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
