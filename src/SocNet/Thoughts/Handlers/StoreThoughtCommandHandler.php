<?php

namespace SocNet\Thoughts\Handlers;

use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Repositories\ThoughtsRepositoryInterface;
use SocNet\Thoughts\Thought;

class StoreThoughtCommandHandler
{
    private $thoughts;

    public function __construct(ThoughtsRepositoryInterface $thoughts)
    {
        $this->thoughts = $thoughts;
    }

    public function handle(StoreThoughtCommand $command)
    {
        $thought = new Thought(
            $command->getAuthor(),
            $command->getContent()
        );

        $this->thoughts->store($thought);
    }
}