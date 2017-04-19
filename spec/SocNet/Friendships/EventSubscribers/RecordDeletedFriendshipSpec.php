<?php

namespace spec\SocNet\Friendships\EventSubscribers;

use PhpSpec\ObjectBehavior;
use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\EventSubscribers\RecordDeletedFriendship;
use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

class RecordDeletedFriendshipSpec extends ObjectBehavior
{
    function let(FriendshipsRecorderInterface $recorder)
    {
        $this->beConstructedWith($recorder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RecordDeletedFriendship::class);
    }

    function it_handles_friendship_was_deleted_events(FriendshipWasDeletedEvent $event, Friendship $friendship, FriendshipsRecorderInterface $recorder)
    {
        $event->getFriendship()->willReturn($friendship);

        $recorder->recordDeleted($friendship)->shouldBeCalled();

        $this->notify($event);
    }
}
