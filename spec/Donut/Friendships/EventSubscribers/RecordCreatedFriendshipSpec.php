<?php

namespace spec\Angelov\Donut\Friendships\EventSubscribers;

use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\EventSubscribers\RecordCreatedFriendship;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

class RecordCreatedFriendshipSpec extends ObjectBehavior
{
    function let(FriendshipsRecorderInterface $recorder)
    {
        $this->beConstructedWith($recorder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RecordCreatedFriendship::class);
    }

    function it_handles_friendship_was_created_events(FriendshipWasCreatedEvent $event, Friendship $friendship, FriendshipsRecorderInterface $recorder)
    {
        $event->getFriendship()->willReturn($friendship);

        $recorder->recordCreated($friendship)->shouldBeCalled();

        $this->notify($event);
    }
}
