<?php

namespace SocNet\Communities\Handlers;

use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Communities\Community;
use SocNet\Communities\Repositories\CommunityRepositoryInterface;

class StoreCommunityCommandHandler
{
    private $communities;

    public function __construct(CommunityRepositoryInterface $communities)
    {
        $this->communities = $communities;
    }

    public function handle(StoreCommunityCommand $command)
    {
        $community = new Community(
            $command->getName(),
            $command->getAuthor(),
            $command->getDescription()
        );

        $this->communities->store($community);
    }
}
