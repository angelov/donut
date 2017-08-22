<?php

namespace Angelov\Donut\Friendships\EventSubscribers;

use Angelov\Donut\Friendships\Events\FriendshipWasCreatedEvent;
use Angelov\Donut\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

class RecordCreatedFriendship
{
    private $recorder;

    public function __construct(FriendshipsRecorderInterface $recorder)
    {
        $this->recorder = $recorder;
    }

    public function notify(FriendshipWasCreatedEvent $event) : void
    {
        // @todo handle recorder exceptions

        $this->recorder->recordCreated(
            $event->getFriendship()
        );
    }
}
