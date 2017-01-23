<?php

namespace AppBundle\FriendsRecommender;

use AppBundle\Entity\User;

interface FriendshipRecorderInterface
{
    public function record(User $first, User $second);
}
