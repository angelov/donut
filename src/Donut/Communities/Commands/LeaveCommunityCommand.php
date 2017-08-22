<?php

namespace Angelov\Donut\Communities\Commands;

use Angelov\Donut\Users\User;
use Angelov\Donut\Communities\Community;

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
