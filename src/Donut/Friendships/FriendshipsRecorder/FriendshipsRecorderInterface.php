<?php

namespace Angelov\Donut\Friendships\FriendshipsRecorder;

use Angelov\Donut\Friendships\Friendship;

interface FriendshipsRecorderInterface
{
    public function recordCreated(Friendship $friendship) : void;

    public function recordDeleted(Friendship $friendship) : void;
}
