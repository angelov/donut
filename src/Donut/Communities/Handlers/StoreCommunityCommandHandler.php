<?php

namespace Angelov\Donut\Communities\Handlers;

use Angelov\Donut\Communities\Commands\StoreCommunityCommand;
use Angelov\Donut\Communities\Community;
use Angelov\Donut\Communities\Repositories\CommunitiesRepositoryInterface;

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
            $command->getId(),
            $command->getName(),
            $command->getAuthor(),
            $command->getDescription()
        );

        $this->communities->store($community);
    }
}
