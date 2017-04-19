<?php

namespace SocNet\Friendships\FriendshipsRecorder;

use SocNet\Friendships\Friendship;

interface FriendshipsRecorderInterface
{
    public function recordCreated(Friendship $friendship) : void;

    public function recordDeleted(Friendship $friendship) : void;
}
