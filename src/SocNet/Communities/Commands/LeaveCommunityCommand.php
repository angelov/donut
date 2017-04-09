<?php

namespace SocNet\Communities\Commands;

use SocNet\Users\User;
use SocNet\Communities\Community;

class LeaveCommunityCommand
{
    private $user;
    private $community;

    public function __construct(User $user, Community $community)
    {
        $this->user = $user;
        $this->community = $community;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function getCommunity() : Community
    {
        return $this->community;
    }
}
