<?php

namespace Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Communities\Commands\LeaveCommunityCommand;
use Angelov\Donut\Communities\Exceptions\CommunityMemberNotFoundException;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

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
