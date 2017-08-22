<?php

namespace Angelov\Donut\Friendships\EventSubscribers;

use Angelov\Donut\Friendships\Events\FriendshipWasDeletedEvent;
use Angelov\Donut\Friendships\FriendshipsRecorder\FriendshipsRecorderInterface;

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
