<?php

namespace spec\SocNet\Friendships\EventSubscribers;

use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\EventSubscribers\RecordCreatedFriendship;
use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

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
