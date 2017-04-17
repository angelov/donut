<?php

namespace SocNet\Communities\Handlers;

use SocNet\Communities\Commands\LeaveCommunityCommand;
use SocNet\Communities\Exceptions\CommunityMemberNotFoundException;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;

class LeaveCommunityCommandHandler
{
    private $communities;

    public function __construct(CommunitiesRepositoryInterface $communities)
    {
        $this->communities = $communities;
    }

    /**
     * @throws CommunityMemberNotFoundException
     */
    public function handle(LeaveCommunityCommand $command) : void
    {
        $community = $command->getCommunity();
        $user = $command->getUser();

        $community->removeMember($user);

        $this->communities->store($community);
    }

}
