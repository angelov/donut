<?php

namespace SocNet\Communities\Exceptions;

use SocNet\Users\User;
use RuntimeException;
use SocNet\Communities\Community;

class CommunityMemberNotFoundException extends RuntimeException
{
    private $member;
    private $community;

    public function __construct(User $member, Community $community)
    {
        $this->member = $member;
        $this->community = $community;

        parent::__construct(sprintf(
            'The given user ["%s"] is not part of the community ["%s"]',
            $member->getName(),
            $community->getName()
        ));
    }

    public function getMember() : User
    {
        return $this->member;
    }

    public function getCommunity() : Community
    {
        return $this->community;
    }
}
