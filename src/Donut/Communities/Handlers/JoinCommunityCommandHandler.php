<?php

namespace Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Communities\Commands\JoinCommunityCommand;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

class JoinCommunityCommandHandler
{
    private $communities;

    public function __construct(CommunitiesRepositoryInterface $communities)
    {
        $this->communities = $communities;
    }

    public function handle(JoinCommunityCommand $command) : void
    {
        $community = $command->getCommunity();
        $user = $command->getUser();

        $community->addMember($user);

        $this->communities->store($community);
    }
}
