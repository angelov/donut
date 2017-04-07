<?php

namespace SocNet\Communities\Handlers;

use SocNet\Communities\Commands\JoinCommunityCommand;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;

class JoinCommunityCommandHandler
{
    private $communities;

    public function __construct(CommunitiesRepositoryInterface $communities)
    {
        $this->communities = $communities;
    }

    public function handle(JoinCommunityCommand $command)
    {
        $community = $command->getCommunity();
        $user = $command->getUser();

        $community->addMember($user);

        $this->communities->store($community);
    }
}
