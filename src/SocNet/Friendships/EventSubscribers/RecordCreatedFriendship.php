<?php

namespace SocNet\Friendships\EventSubscribers;

use SocNet\Friendships\Events\FriendshipWasCreatedEvent;
use SocNet\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

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
