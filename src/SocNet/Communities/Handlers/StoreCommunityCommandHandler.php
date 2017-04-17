<?php

namespace SocNet\Communities\Handlers;

use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Communities\Community;
use SocNet\Communities\Repositories\CommunitiesRepositoryInterface;

class StoreCommunityCommandHandler
{
    private $communities;

    public function __construct(CommunitiesRepositoryInterface $communities)
    {
        $this->communities = $communities;
    }

    public function handle(StoreCommunityCommand $command) : void
    {
        $community = new Community(
            $command->getName(),
            $command->getAuthor(),
            $command->getDescription()
        );

        $this->communities->store($community);
    }
}
