<?php

namespace SocNet\Friendships\EventSubscribers;

use SocNet\Friendships\Events\FriendshipWasDeletedEvent;
use SocNet\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

class RecordDeletedFriendship
{
    private $recorder;

    public function __construct(FriendshipsRecorderInterface $recorder)
    {
        $this->recorder = $recorder;
    }

    public function notify(FriendshipWasDeletedEvent $event) : void
    {
        // @todo handle exceptions

        $this->recorder->recordDeleted($event->getFriendship());
    }
}
