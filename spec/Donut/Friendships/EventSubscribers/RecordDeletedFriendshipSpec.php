<?php

namespace spec\Angelov\Donut\Friendships\EventSubscribers;

use PhpSpec\ObjectBehavior;
use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\EventSubscribers\RecordDeletedFriendship;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

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
